<div>
    <div id="modal1" class="modal">
        <div class="modal-content">
            <h4>Modal Header</h4>
            <p>A bunch of text</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let elems = document.querySelectorAll('.modal');

            elems.forEach((elem) => {
                let instance = M.Modal.getInstance(elem);
                instance.open()
            })
        });
    </script>
</div>
