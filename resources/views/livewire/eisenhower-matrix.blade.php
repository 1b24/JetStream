<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($quadrantes as $q => $qdata)
            <div class="p-5 rounded-xl shadow-lg border {{ $qdata['bg'] }}">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-xl {{ $qdata['titleColor'] }}">
                        {{ $qdata['titulo'] }}
                    </h2>

                    <button wire:click="novaTarefa({{ $q }})"
                        class="px-3 py-1 text-sm text-white rounded {{ $qdata['buttonColor'] }} hover:brightness-110 transition">
                        + Nova tarefa
                    </button>
                </div>

                <div id="quadrante-{{ $q }}" data-quadrante="{{ $q }}" class="min-h-[180px] space-y-3">
                    @foreach($tasks->where('quadrante', $q) as $t)
                        <div class="task-card p-3 bg-white border rounded-lg shadow hover:shadow-md transition flex items-start gap-2"
                            data-id="{{ $t->id }}"
                            style="{{ $editando === $t->id ? 'border:2px solid ' . $qdata['editBorder'] . '; cursor:default;' : '' }}">

                            {{-- HANDLE PRA ARRASTAR --}}
                            <div class="drag-handle select-none text-gray-400 pt-1 cursor-grab active:cursor-grabbing">
                                ≡
                            </div>

                            {{-- CHECKBOX CONCLUÍDO --}}
                            <input type="checkbox" class="no-drag mt-1" wire:click="toggleConcluida({{ $t->id }})"
                                @checked($t->completed) />

                            <div class="flex-1">
                                @if($editando === $t->id)
                                    <input type="text"
                                        wire:model.defer="novoTitulo"
                                        wire:keydown.enter="salvarTarefa({{ $t->id }})"
                                        wire:keydown.escape="$set('editando', null)"
                                        class="w-full border-gray-300 rounded"
                                        autofocus />
                                @else
                                    <div class="{{ $t->completed ? 'line-through text-gray-400' : '' }}"
                                        wire:dblclick="editarTarefa({{ $t->id }})">
                                        {{ $t->titulo }}
                                    </div>
                                @endif
                            </div>

                            {{-- BOTÕES EDITAR / EXCLUIR --}}
                            <div class="no-drag flex items-center gap-2">
                                <button type="button"
                                    class="text-xs px-2 py-1 rounded bg-gray-200 hover:bg-gray-300"
                                    wire:click="editarTarefa({{ $t->id }})">
                                    Editar
                                </button>

                                <button type="button"
                                    class="text-xs px-2 py-1 rounded bg-red-500 text-white hover:bg-red-600"
                                    wire:click="confirmarExclusao({{ $t->id }})">
                                    Excluir
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>

    {{-- MODAL CONFIRMAÇÃO --}}
    @if($confirmandoExclusaoId)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-bold mb-3">Excluir tarefa?</h3>
                <p class="text-sm text-gray-600 mb-5">
                    Essa ação não pode ser desfeita.
                </p>

                <div class="flex justify-end gap-2">
                    <button wire:click="cancelarExclusao" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">
                        Cancelar
                    </button>

                    <button wire:click="excluirTarefa({{ $confirmandoExclusaoId }})"
                        class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            console.log('livewire:init EisenhowerMatrix');

            const componentId = @js($this->getId());
            let sortables = {};

            function initSortables() {
                if (!window.Livewire || !window.Livewire.find) {
                    console.error('Livewire global não encontrado');
                    return;
                }

                [1, 2, 3, 4].forEach(q => {
                    const el = document.getElementById('quadrante-' + q);
                    if (!el || sortables[q]) return;

                    sortables[q] = new Sortable(el, {
                        group: "tasks",
                        animation: 150,
                        ghostClass: "opacity-50",
                        handle: ".drag-handle",

                        onEnd: evt => {
                            const id = evt.item.dataset.id;
                            const quadrante = evt.to.dataset.quadrante;

                            const ids = [...evt.to.querySelectorAll(".task-card")]
                                .map(card => card.dataset.id);

                            console.log('onEnd →', { id, quadrante, ids });

                            window.Livewire
                                .find(componentId)
                                .call('syncDrag', id, quadrante, ids);
                        },
                    });
                });
            }

            initSortables();

            document.addEventListener('livewire:update', initSortables);
            document.addEventListener('livewire:navigated', initSortables);
        });
    </script>
</div>
