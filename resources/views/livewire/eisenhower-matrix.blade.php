@php
    $quadrantes = [
        1 => [
            'titulo'      => 'Urgente e Importante',
            'bg'          => 'bg-red-100',
            'titleColor'  => 'text-red-700',
            'buttonColor' => 'bg-red-600',
        ],
        2 => [
            'titulo'      => 'Não Urgente e Importante',
            'bg'          => 'bg-yellow-100',
            'titleColor'  => 'text-yellow-700',
            'buttonColor' => 'bg-yellow-600',
        ],
        3 => [
            'titulo'      => 'Urgente e Não Importante',
            'bg'          => 'bg-blue-100',
            'titleColor'  => 'text-blue-700',
            'buttonColor' => 'bg-blue-600',
        ],
        4 => [
            'titulo'      => 'Não Urgente e Não Importante',
            'bg'          => 'bg-green-100',
            'titleColor'  => 'text-green-700',
            'buttonColor' => 'bg-green-600',
        ],
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    @foreach($quadrantes as $q => $qdata)
        <div class="p-5 rounded-xl shadow-lg border {{ $qdata['bg'] }}">

            <h2 class="font-bold text-xl mb-4 {{ $qdata['titleColor'] }}">
                {{ $qdata['titulo'] }}
            </h2>

            <button
                wire:click="novaTarefa({{ $q }})"
                class="px-3 py-1 text-sm text-white rounded {{ $qdata['buttonColor'] }} hover:brightness-110 transition">
                + Nova tarefa
            </button>

            <div id="quadrante-{{ $q }}" data-quadrante="{{ $q }}" class="mt-4 min-h-[160px] space-y-3">
                @foreach($tasks->where('quadrante', $q) as $t)
    <div
        class="p-3 bg-white border rounded-lg shadow hover:shadow-md transition cursor-pointer"
        data-id="{{ $t->id }}"
        wire:click="editarTarefa({{ $t->id }})"
    >

        {{-- MODO EDIÇÃO --}}
        @if($editando === $t->id)

            <input type="text"
                wire:model.defer="novoTitulo"
                wire:keydown.enter="salvarTarefa({{ $t->id }})"
                wire:keydown.escape="$set('editando', null)"
                class="w-full border-gray-300 rounded"
                autofocus
            >

        @else
            {{-- MODO VISUALIZAÇÃO --}}
            {{ $t->titulo }}
        @endif

    </div>
@endforeach

            </div>

        </div>
    @endforeach

</div>

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    function initSortables() {
        [1, 2, 3, 4].forEach(q => {
            let el = document.getElementById('quadrante-' + q);
            if (!el) return;

            new Sortable(el, {
                group: "tasks",
                animation: 180,
                ghostClass: "opacity-50",
                onEnd: evt => {
                    let id = evt.item.dataset.id;
                    let novoQuadrante = evt.to.dataset.quadrante;
                    Livewire.emit('moverTarefa', id, novoQuadrante);
                }
            });
        });
    }

    document.addEventListener('livewire:load', initSortables);
    document.addEventListener('livewire:navigated', initSortables);
</script>
