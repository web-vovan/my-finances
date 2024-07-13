<div>
    <div class="row">
        <form class="category-edit-form" wire:submit="save">
            <div class="row">
                <div class="col">
                    <div class="input-field"
                    >
                        <i class="material-icons prefix">mode_edit</i>
                        <input required id="icon_prefix2" class="materialize-textarea" wire:model="name" />
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col mb-4">
                    <div class="switch">
                        <label>
                            <input type="checkbox" wire:model="isHide">
                            <span class="lever ml-0"></span>
                            Скрыть в общем списке
                        </label>
                    </div>
                </div>

                <div class="col center-align">
                    <a href="/categories" class="close-button waves-effect waves-green btn-flat">Отмена</a>
                    <button type="submit" class="waves-effect waves-light btn-large"><i class="material-icons left">save</i>Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>
