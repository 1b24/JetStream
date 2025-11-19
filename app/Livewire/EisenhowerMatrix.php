<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class EisenhowerMatrix extends Component
{
    public $tasks;

    protected $listeners = ['moverTarefa' => 'moverTarefa'];

    public function mount()
{
    $this->carregarTarefas();
}

    public function novaTarefa($quadrante)
    {
        Task::create([
            'user_id' => auth()->id(),
            'titulo' => 'Nova tarefa',
            'quadrante' => $quadrante,
        ]);

        $this->mount();
    }

    public function moverTarefa($id, $quadrante)
    {
        Task::find($id)->update([
            'quadrante' => $quadrante
        ]);

        $this->mount();
    }

    public function render()
    {
        return view('livewire.eisenhower-matrix');
    }
    public $editando = null;
public $novoTitulo = '';

public function editarTarefa($id)
{
    $tarefa = Task::find($id);

    if ($tarefa) {
        $this->editando = $id;
        $this->novoTitulo = $tarefa->titulo;
    }
}

public function salvarTarefa($id)
{
    $tarefa = Task::find($id);

    if ($tarefa) {
        $tarefa->update([
            'titulo' => $this->novoTitulo
        ]);
    }

    $this->editando = null;
    $this->novoTitulo = '';
    $this->carregarTarefas(); // OU $this->tasks = Task::...
}
public function carregarTarefas()
{
    $this->tasks = Task::where('user_id', auth()->id())->get();
}
}
