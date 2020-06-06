<div class="modal fade" id="bookmarkDeleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h2 class="modal-title" style="margin: 0;">Удаление вкладки</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    Для удаления закладки введите пароль, указанный при её создании
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-center">
                        <div style="width: 50%;">
                            <input type="password" name="PASSWORD" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="bookmarkDeleteModal__deleteBtn">Удалить</button>
            </div>
        </div>
    </div>
</div>