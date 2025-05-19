<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Lab5Controller;
use Illuminate\Support\Facades\Storage;

class Lab9_2Controller extends Controller
{
    public function index()
    {
        $images = Image::latest()->get();
        return view('data_base.lab6.index', compact('images'));
    }

    public function generateImage()
    {
        $lab5 = new Lab5Controller();
        return $lab5->generateImage();
    }

    public function saveGeneratedImage(Request $request)
    {
        try {
            $request->validate(['image_data' => 'required|string']);

            // Декодирование изображения
            $imageData = explode(',', $request->image_data)[1];
            $decodedImage = base64_decode($imageData);

            if (!$decodedImage) {
                throw new \Exception('Ошибка декодирования изображения');
            }

            // Подготовка путей
            $fileName = 'img_'.time().'_'.bin2hex(random_bytes(4)).'.png';
            $storageRelativePath = "images/$fileName";  // images/filename.png
            $storageFullPath = storage_path("app/public/$storageRelativePath"); // Полный серверный путь

            // Сохранение файла
            if (!file_exists(dirname($storageFullPath))) {
                mkdir(dirname($storageFullPath), 0755, true);
            }

            file_put_contents($storageFullPath, $decodedImage);

            // Проверка существования файла
            if (!file_exists($storageFullPath)) {
                throw new \Exception("Файл не сохранён по пути: $storageFullPath");
            }

            // Сохранение в БД
            $image = Image::create([
                'filename' => $fileName,
                'path' => "storage/$storageRelativePath" // storage/images/filename.png
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Изображение сохранено',
                'path' => asset($image->path), // Полный URL
                'full_path' => $storageFullPath // Для отладки
            ]);

        } catch (\Exception $e) {
            \Log::error("Ошибка сохранения: ".$e->getMessage()."\n".$e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: '.$e->getMessage(),
                'trace' => env('APP_DEBUG') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    private function getImageSize($path)
    {
        try {
            // Удаляем 'storage/' из пути для проверки
            $relativePath = str_replace('storage/', '', $path);

            if (Storage::disk('public')->exists($relativePath)) {
                $sizeInBytes = Storage::disk('public')->size($relativePath);
                return round($sizeInBytes / 1024, 2) . ' KB';
            }
            return 'N/A';
        } catch (\Exception $e) {
            \Log::error("Error getting image size: " . $e->getMessage());
            return 'N/A';
        }
    }

    public function getImages()
    {
        $images = Image::latest()->get();

        $formattedImages = $images->map(function($image) {
            return [
                'id' => $image->id,
                'created_at' => $image->created_at->format('d.m.Y H:i'),
                'size' => $this->getImageSize($image->path),
                'path' => asset($image->path) // Используем asset() для генерации полного URL
            ];
        });

        return response()->json($formattedImages);
    }
}