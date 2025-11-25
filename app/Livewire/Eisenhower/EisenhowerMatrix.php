<?php

namespace App\Livewire\Eisenhower;

use Livewire\Component;
use Livewire\Attributes\Name;
use App\Models\Task;
use App\Support\Quadrantes;

#[Name('eisenhower-matrix')]
class EisenhowerMatrix extends Component
{
    public $tasks;
    public $editando = null;
    public $novoTitulo = '';
    public $novaDescricao = '';
    public $confirmandoExclusaoId = null;

    public array $quadrantes = Quadrantes::LISTA;

    public function mount()
    {
        $this->carregarTarefas();
        $this->normalizarOrdemSePreciso();
    }

    /** Usa o scope owned() e deixa o codigo limpo */
    private function getTask($id)
    {
        return Task::owned()->find($id);
    }

    public function carregarTarefas()
    {
        $this->tasks = Task::owned()
            ->ordered()
            ->get();
    }

    private function normalizarOrdemSePreciso()
    {
        foreach ([1, 2, 3, 4] as $q) {
            $qs = Task::owned()
                ->where('quadrante', $q)
                ->orderBy('id')
                ->get();

            if ($qs->contains(fn($t) => is_null($t->ordem))) {
                foreach ($qs as $i => $t) {
                    $t->update(['ordem' => $i + 1]);
                }
            }
        }
    }

    public function novaTarefa($quadrante)
    {
        $maxOrdem = Task::owned()
            ->where('quadrante', $quadrante)
            ->max('ordem');

        Task::create([
            'user_id'   => auth()->id(),
            'titulo'    => 'Nova tarefa',
            'descricao' => null,
            'quadrante' => (int) $quadrante,
            'completed' => false,
            'ordem'     => ($maxOrdem ?? 0) + 1,
        ]);

        $this->carregarTarefas();
    }

    /** Chamado pelo JS */
    public function syncDrag($id, $quadrante, $ids)
    {
        $this->editando = null;
        $quadrante = (int) $quadrante;

        if ($task = $this->getTask($id)) {
            $task->update(['quadrante' => $quadrante]);
        }

        foreach ($ids as $index => $taskId) {
            if ($t = $this->getTask($taskId)) {
                $t->update(['ordem' => $index + 1]);
            }
        }

        $this->carregarTarefas();
    }

    public function editarTarefa($id)
    {
        if ($task = $this->getTask($id)) {
            $this->editando = $id;
            $this->novoTitulo = $task->titulo;
            $this->novaDescricao = $task->descricao ?? '';
        }
    }

    public function salvarTarefa($id)
    {
        if ($task = $this->getTask($id)) {
            $titulo = trim($this->novoTitulo) ?: 'Sem titulo';
            $descricao = trim((string) $this->novaDescricao) ?: null;

            $task->update([
                'titulo' => $titulo,
                'descricao' => $descricao,
            ]);
        }

        $this->cancelarEdicao();
        $this->carregarTarefas();
    }

    public function cancelarEdicao()
    {
        $this->editando = null;
        $this->novoTitulo = '';
        $this->novaDescricao = '';
    }

    public function toggleConcluida($id)
    {
        if ($task = $this->getTask($id)) {
            $task->update(['completed' => !$task->completed]);
        }

        $this->carregarTarefas();
    }

    public function confirmarExclusao($id)
    {
        $this->confirmandoExclusaoId = $id;
    }

    public function cancelarExclusao()
    {
        $this->confirmandoExclusaoId = null;
    }

    public function excluirTarefa($id)
    {
        if ($task = $this->getTask($id)) {
            $task->delete();
        }

        $this->confirmandoExclusaoId = null;

        $this->normalizarOrdemSePreciso();
        $this->carregarTarefas();
    }

    public function render()
    {
        return view('livewire.eisenhower.matrix');
    }
}
