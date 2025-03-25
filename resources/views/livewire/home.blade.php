<div>
    <div class="center-align home-page flex justify-content-center align-items-center">
        <div class="wrap-home">
            <div class="wrap-btn">
                <a href="/costs/create" class="btn-floating btn-large waves-effect waves-light">
                    <i class="material-icons">add</i>
                </a>
            </div>
            <div class="wrap-history flex justify-content-between align-items-center">
                <div class="left-align">
                    <div>Расходы за {{ $month }}:</div>
                    <div class="flow-text font-weight-bold">{{ priceFormat($monthTotal) }}</div>
                    <div class="grey-text">{{ priceFormat($monthTotal2) }}</div>
                </div>
                <div class="right-align">
                    <div>Расходы за сегодня:</div>
                    <div class="flow-text font-weight-bold">{{ priceFormat($todayTotal) }}</div>
                    <div class="grey-text">{{ priceFormat($todayTotal2) }}</div>
                </div>
            </div>
            <div class="wrap-cost">
                <table>
                    <tbody>
                        @foreach($costs as $key => $cost)
                            <tr>
                                <td>
                                    {{ $cost->date->isoFormat('D MMM') }}
                                    <div class="grey-text">{{ $costs2[$key]['date'] }}</div>
                                </td>
                                <td>
                                    {{ $cost->priceFormat }}
                                    <div class="grey-text">{{ $costs2[$key]['price'] }}</div>
                                </td>
                                <td>
                                    {{ $cost->category?->name }}
                                    <div class="grey-text">{{ $costs2[$key]['category'] }}</div>
                                </td>
                                <td>
                                    <a href="#" class="secondary-content dropdown-trigger" data-target="dropdown{{ $key }}">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul id="dropdown{{ $key }}"  class="dropdown-content edit-list">
                                        <li>
                                            <a href="/costs/{{ $cost->uuid }}/edit">
                                                <i class="material-icons mr-0">edit</i>
                                            </a>
                                        </li>
                                        <li class="divider" tabindex="-1"></li>
                                        <li>
                                            <a href="#" class="delete-btn" data-cost-uuid="{{ $cost->uuid }}" data-cost2-uuid="{{ $costs2[$key]['uuid'] }}">
                                                <i class="material-icons mr-0">delete</i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="confirm-modal" class="modal" wire:ignore>
        <div class="modal-content">
            <h5 class="center-align mt-0">Удалить запись?</h5>
        </div>
        <div class="modal-footer center-align">
            <a href="#" class="close-button waves-effect waves-green btn-flat">Отмена</a>
            <a class="waves-effect waves-light btn confirm-button"><i class="material-icons left">delete</i>Удалить</a>
        </div>
    </div>
    @script
    <script>
        let deleteButtons = document.querySelectorAll('.delete-btn')
        let confirmButton = document.querySelector('.confirm-button')
        let closeButton = document.querySelector('.close-button')

        let modal = document.querySelector('#confirm-modal')
        let instance = M.Modal.init(modal);

        closeButton.addEventListener('click', (event) => {
            instance.close()
        })

        deleteButtons.forEach((btn) => {
            btn.addEventListener('click', (event) => {
                instance.open()
                confirmButton.dataset.costId = event.currentTarget.dataset.costId
                confirmButton.dataset.cost2Id = event.currentTarget.dataset.cost2Id
            })
        })

        confirmButton.addEventListener('click', (event) => {
            Livewire.dispatch('delete-cost', {
                'uuid': event.currentTarget.dataset.costUuid,
                'uuid2': event.currentTarget.dataset.cost2Uuid
            });

            instance.close()
        })
    </script>
    @endscript
</div>
