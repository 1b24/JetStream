<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class EisenhowerMatrix extends Component
{
    public $tasks;

    public $editando = null;
    public $novoTitulo = '';

    public $confirmandoExclusaoId = null;

    /** Quadrantes configurados aqui */
    public array $quadrantes = [
        1 => [
            'titulo'      => 'Urgente e Importante',
            'bg'          => 'bg-red-100',
            'titleColor'  => 'text-red-700',
            'buttonColor' => 'bg-red-600',
            'editBorder'  => '#dc2626',
        ],
        2 => [
            'titulo'      => 'Não Urgente e Importante',
            'bg'          => 'bg-yellow-100',
            'titleColor'  => 'text-yellow-700',
            'buttonColor' => 'bg-yellow-600',
            'editBorder'  => '#ca8a04',
        ],
        3 => [
            'titulo'      => 'Urgente e Não Importante',
            'bg'          => 'bg-blue-100',
            'titleColor'  => 'text-blue-700',
            'buttonColor' => 'bg-blue-600',
            'editBorder'  => '#2563eb',
        ],
        4 => [
            'titulo'      => 'Não Urgente e Não Importante',
            'bg'          => 'bg-green-100',
            'titleColor'  => 'text-green-700',
            'buttonColor' => 'bg-green-600',
            'editBorder'  => '#16a34a',
        ],
    ];

    public function mount()
    {
        $this->carregarTarefas();
        $this->normalizarOrdemSePreciso();
    }

    public function carregarTarefas()
    {
        $this->tasks = Task::where('user_id', auth()->id())
            ->orderBy('quadrante')
            ->orderByRaw('ordem IS NULL')
            ->orderBy('ordem')
            ->get();
    }

    private function normalizarOrdemSePreciso()
    {
        foreach ([1, 2, 3, 4] as $q) {
            $qs = Task::where('user_id', auth()->id())
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
        $maxOrdem = Task::where('user_id', auth()->id())
            ->where('quadrante', $quadrante)
            ->max('ordem');

        Task::create([
            'user_id'   => auth()->id(),
            'titulo'    => 'Nova tarefa',
            'quadrante' => (int)$quadrante,
            'completed' => false,
            'ordem'     => ($maxOrdem ?? 0) + 1,
        ]);

        $this->carregarTarefas();
    }

    /** Chamado pelo JS: Livewire.find(componentId).call('syncDrag', id, quadrante, ids) */
    public function syncDrag($id, $quadrante, $ids)
    {
        $this->editando = null;

        $quadrante = (int)$quadrante;

        if ($task = Task::where('user_id', auth()->id())->find($id)) {
            $task->update(['quadrante' => $quadrante]);
        }

        foreach ($ids as $index => $taskId) {
            Task::where('user_id', auth()->id())
                ->where('id', $taskId)
                ->update(['ordem' => $index + 1]);
        }

        $this->carregarTarefas();
    }

    public function editarTarefa($id)
    {
        if ($task = Task::where('user_id', auth()->id())->find($id)) {
            $this->editando = $id;
            $this->novoTitulo = $task->titulo;
        }
    }

    public function salvarTarefa($id)
    {
        if ($task = Task::where('user_id', auth()->id())->find($id)) {
            $task->update([
                'titulo' => trim($this->novoTitulo) ?: 'Sem título',
            ]);
        }

        $this->editando = null;
        $this->novoTitulo = '';
        $this->carregarTarefas();
    }

    public function toggleConcluida($id)
    {
        if ($task = Task::where('user_id', auth()->id())->find($id)) {
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
        Task::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();

        $this->confirmandoExclusaoId = null;

        $this->normalizarOrdemSePreciso();
        $this->carregarTarefas();
    }

    public function render()
    {
        return view('livewire.eisenhower-matrix');
    }
}
