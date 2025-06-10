<div class="category-page">
    <ul class="collection with-header category-list">
        <li class="collection-header flex justify-content-between align-items-center">
            <h4 class="mt-3 mb-3">Категории</h4>
            <a href="/categories/create" class="btn-floating waves-effect waves-light">
                <i class="material-icons">add</i>
            </a>
        </li>

        @foreach($categories as $key => $category)
            <li class="collection-item">
                <div>{{ $category['name'] }}
                    <a href="#" class="secondary-content dropdown-trigger" data-target="dropdown{{ $key }}">
                        <i class="material-icons">more_vert</i>
                    </a>
                </div>

                <ul id="dropdown{{ $key }}"  class="dropdown-content edit-list">
                    <li class="center-align">
                        <a href="/categories/{{ $category['uuid'] }}/edit" >
                            <i class="material-icons mr-0">edit</i>
                        </a>
                    </li>
                    <li class="divider" tabindex="-1"></li>
                    <li>
                        <a href="#" class="delete-btn" data-category-uuid="{{ $category['uuid'] }}">
                            <i class="material-icons mr-0">delete</i>
                        </a>
                    </li>
                </ul>
            </li>
        @endforeach
    </ul>

    <div id="confirm-modal" class="modal" wire:ignore>
        <div class="modal-content">
            <h5 class="center-align mt-0">Удалить категорию?</h5>
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
                confirmButton.dataset.categoryUuid = event.currentTarget.dataset.categoryUuid
            })
        })

        confirmButton.addEventListener('click', (event) => {
            Livewire.dispatch('delete-category', {
                'uuid': event.currentTarget.dataset.categoryUuid
            });

            instance.close()
        })
    </script>
    @endscript
</div>
