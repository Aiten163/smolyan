<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Галерея изображений</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gallery-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .generated-img {
            max-height: 300px;
            margin: 15px auto;
            display: block;
        }
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .action-area {
            border: 2px dashed #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="text-center mb-4">
                <i class="fas fa-images me-2"></i>Галерея изображений
            </h1>

            <div class="action-area text-center">
                <div class="row g-3">
                    <div class="col-md-6">
                        <button id="generateBtn" class="btn btn-primary w-100">
                            <i class="fas fa-magic me-1"></i> Сгенерировать изображение
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button id="saveBtn" class="btn btn-success w-100" disabled>
                            <i class="fas fa-save me-1"></i> Сохранить изображение
                        </button>
                    </div>
                </div>

                <div class="mt-3">
                    <img id="generatedImage" class="generated-img img-thumbnail d-none">
                </div>
            </div>

            <div id="galleryContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($images as $image)
                    <div class="col">
                        <div class="card h-100">
                            <img src="{{ asset($image->path) }}" class="gallery-img card-img-top" alt="Image">
                            <div class="card-body">
                                <h5 class="card-title">Image #{{ $image->id }}</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        {{ $image->created_at->format('d.m.Y H:i') }}
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-file-image me-2"></i>
                                        @php
                                            $size = Storage::disk('public')->size('images/'.$image->filename);
                                            echo round($size / 1024, 2).' KB';
                                        @endphp
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($images->isEmpty())
                <div id="emptyGallery" class="text-center py-5">
                    <i class="fas fa-image fa-4x mb-3 text-muted"></i>
                    <h4 class="text-muted">Нет сохранённых изображений</h4>
                    <p>Сгенерируйте и сохраните изображения с помощью кнопок выше</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Toast для уведомлений -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Уведомление</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastEl = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastEl);
        const generateBtn = document.getElementById('generateBtn');
        const saveBtn = document.getElementById('saveBtn');
        const generatedImage = document.getElementById('generatedImage');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentImageData = null;

        // Генерация изображения
        generateBtn.addEventListener('click', async function() {
            generateBtn.disabled = true;
            generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Генерация...';

            try {
                const response = await fetch("{{ route('lab9_2.generateImage') }}", {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Ошибка генерации изображения');

                currentImageData = await response.text();

                generatedImage.src = currentImageData;
                generatedImage.classList.remove('d-none');
                saveBtn.disabled = false;

            } catch (error) {
                showToast(error.message, 'danger');
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<i class="fas fa-magic me-1"></i> Сгенерировать изображение';
            }
        });

        // Сохранение изображения
        saveBtn.addEventListener('click', async function() {
            if (!currentImageData) return;

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Сохранение...';

            try {
                const response = await fetch("{{ route('lab9_2.saveGeneratedImage') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ image_data: currentImageData })
                });

                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'Ошибка сохранения изображения');

                showToast(data.message, 'success');

                // Перенаправляем после небольшой задержки
                setTimeout(() => {
                    location.reload();
                }, 1500);

            } catch (error) {
                showToast(error.message, 'danger');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-1"></i> Сохранить изображение';
            }
        });

        // Вспомогательные функции
        function showToast(message, type = 'success') {
            const toastBody = document.querySelector('.toast-body');
            toastBody.textContent = message;
            toastBody.className = 'toast-body';
            toastBody.classList.add(`text-${type}`);
            toast.show();
        }
    });
</script>
</body>
</html>