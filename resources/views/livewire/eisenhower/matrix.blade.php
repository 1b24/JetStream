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
                        <div class="task-card p-3 bg-white border rounded-lg shadow hover:shadow-md transition flex items-start gap-3"
                            data-id="{{ $t->id }}"
                            style="{{ $editando === $t->id ? 'border:2px solid ' . $qdata['editBorder'] . '; cursor:default;' : '' }}">

                            {{-- HANDLE PARA ARRASTAR --}}
                            <div class="drag-handle select-none text-gray-400 pt-1 cursor-grab active:cursor-grabbing">
                                ::
                            </div>

                            {{-- CHECKBOX CONCLUIDO --}}
                            <input type="checkbox" class="no-drag mt-1" wire:click="toggleConcluida({{ $t->id }})"
                                @checked($t->completed) />

                            <div class="flex-1">
                                @if($editando === $t->id)
                                    <div class="space-y-2">
                                        <input type="text"
                                            wire:model.defer="novoTitulo"
                                            wire:keydown.enter="salvarTarefa({{ $t->id }})"
                                            class="w-full border-gray-300 rounded"
                                            placeholder="Titulo"
                                            autofocus />

                                        <textarea wire:model.defer="novaDescricao"
                                            class="w-full border-gray-300 rounded text-sm"
                                            rows="3"
                                            placeholder="Descricao (opcional)"></textarea>

                                        <div class="flex gap-2">
                                            <button type="button"
                                                class="px-3 py-1 rounded bg-emerald-600 text-white text-xs hover:bg-emerald-700"
                                                wire:click="salvarTarefa({{ $t->id }})">
                                                Salvar
                                            </button>
                                            <button type="button"
                                                class="px-3 py-1 rounded bg-gray-200 text-xs hover:bg-gray-300"
                                                wire:click="cancelarEdicao">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-1">
                                        <div class="{{ $t->completed ? 'line-through text-gray-400' : '' }}"
                                            wire:dblclick="editarTarefa({{ $t->id }})">
                                            {{ $t->titulo }}
                                        </div>

                                        @if($t->descricao)
                                            <div class="text-sm text-gray-600 whitespace-pre-line">
                                                {{ $t->descricao }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- BOTOES EDITAR / EXCLUIR --}}
                            <div class="no-drag flex items-center gap-2">
                                <button type="button"
                                    class="text-xs px-2.5 py-1 rounded border border-gray-300 text-gray-700 hover:bg-gray-100 flex items-center gap-1"
                                    wire:click="editarTarefa({{ $t->id }})">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 19.5 7.125m-2.638-2.638-9.42 9.42a4.5 4.5 0 0 0-1.17 2.064l-.58 2.321 2.32-.58a4.5 4.5 0 0 0 2.065-1.17l9.42-9.42m-2.638-2.638 2.638 2.638" />
                                    </svg>
                                    Editar
                                </button>

                                <button type="button"
                                    class="text-xs px-2.5 py-1 rounded bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 flex items-center gap-1"
                                    wire:click="confirmarExclusao({{ $t->id }})">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75h4.5m-7.5 0h10.5M4.5 6.75h15m-1.5 0-.867 10.403a2.25 2.25 0 0 1-2.245 2.097H8.112a2.25 2.25 0 0 1-2.245-2.097L5 6.75m2.25 0V5.25A1.5 1.5 0 0 1 8.75 3.75h6.5a1.5 1.5 0 0 1 1.5 1.5V6.75" />
                                    </svg>
                                    Excluir
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>

    {{-- MODAL CONFIRMACAO --}}
    @if($confirmandoExclusaoId)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-bold mb-3">Excluir tarefa?</h3>
                <p class="text-sm text-gray-600 mb-5">
                    Essa acao nao pode ser desfeita.
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
            const componentId = @js($this->getId());
            let sortables = {};

            function initSortables() {
                if (!window.Livewire || !window.Livewire.find) {
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
