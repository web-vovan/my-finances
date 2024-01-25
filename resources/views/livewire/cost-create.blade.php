<div class="cost-page">
    <div class="row">
        <form class="cost-form" wire:submit="save">
            <div class="row">
                <div class="col">
                    <div class="input-field" x-init="$refs.costField.click()">
                        <i class="material-icons prefix">attach_money</i>
                        <input required id="icon_prefix" type="number" class="center-align price-field" wire:model="price" x-ref="costField" inputmode="numeric">
                    </div>
                </div>
                <div class="col">
                    <div class="category-list">
                        @foreach($categories as $category)
                            <a
                                @class([
                                    'waves-effect waves-light btn mb-2',
                                    'grey lighten-1' => $category->id !== $categoryId
                                ])
                                wire:click.prevent="selectCategory({{ $category->id }})"
                            >
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col">
                    <div class="input-field" wire:ignore>
                        <i class="material-icons prefix">date_range</i>
                        <input type="text" class="datepicker" >
                    </div>
                </div>

                <div class="col">
                    <div class="input-field">
                        <i class="material-icons prefix">mode_edit</i>
                        <textarea id="icon_prefix2" class="materialize-textarea" wire:model="comment"></textarea>
                    </div>
                </div>

                <div class="col center-align">
                    <button type="submit" class="waves-effect waves-light btn-large"><i class="material-icons left">save</i>Сохранить</button>
                </div>
            </div>
        </form>
    </div>

    @script
        <script>
            let elem = document.querySelectorAll('.datepicker');
            let instance = M.Datepicker.getInstance(elem[0]);

            instance.setDate(new Date(Date.parse("{{ $date }}")))
            instance.setInputValue()

            elem[0].addEventListener('change', function(event) {
                $wire.$set('date', event.target.value)
            })
        </script>
    @endscript
</div>
