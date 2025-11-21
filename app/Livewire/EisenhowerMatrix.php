<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Livewire\Attributes\On;

class EisenhowerMatrix extends Component
{
    public $tasks;
    public $editando = null;
    public $novoTitulo = '';

    public function mount()
    {
        $this->carregarTarefas();
    }

    public function carregarTarefas()
    {
        $this->tasks = Task::where('user_id', auth()->id())->get();
    }

    public function novaTarefa($quadrante)
    {
        Task::create([
            'user_id' => auth()->id(),
            'titulo' => 'Nova tarefa',
            'quadrante' => $quadrante,
        ]);

        $this->carregarTarefas();
    }

    #[On('moverTarefa')]
    public function moverTarefa($id, $quadrante)
    {
        $this->editando = null;

        if ($task = Task::find($id)) {
            $task->update([
                'quadrante' => (int) $quadrante
            ]);
        }

        $this->carregarTarefas();
    }

    public function editarTarefa($id)
    {
        $task = Task::find($id);

        if ($task) {
            $this->editando = $id;
            $this->novoTitulo = $task->titulo;
        }
    }

    public function salvarTarefa($id)
    {
        $task = Task::find($id);

        if ($task) {
            $task->update([
                'titulo' => $this->novoTitulo
            ]);
        }

        $this->editando = null;
        $this->novoTitulo = '';
        $this->carregarTarefas();
    }

    public function render()
    {
        return view('livewire.eisenhower-matrix');
    }
}
