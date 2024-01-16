<div>
    <div class="row">
        <form class="user-login-form" wire:submit="loginUser">
            <div class="row">
                <div class="col">
                    <div class="input-field">
                        <i class="material-icons prefix">person</i>
                        <input required id="icon_prefix" type="text" wire:model="login">
                    </div>
                </div>

                <div class="col">
                    <div class="input-field">
                        <i class="material-icons prefix">security</i>
                        <input required id="icon_prefix" type="text" wire:model="password">
                    </div>
                </div>

                <div class="col center-align">
                    <button type="submit" class="waves-effect waves-light btn-large">
                        Войти
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
