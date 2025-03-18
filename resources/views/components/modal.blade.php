<div class="modal fade" id="{{ $idModal ?? 'defaultModal' }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title ?? 'Заголовок' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>

            @isset($method)
                <form action="{{ $link }}" method="POST">
                    @method($method)
                    @csrf
                    @endisset

                    <div class="modal-body text-start">
                        {{ $slot }}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">{{ $button ?? 'ОК' }}</button>
                    </div>

                    @isset($method)
                </form>
            @endisset

        </div>
    </div>
</div>
