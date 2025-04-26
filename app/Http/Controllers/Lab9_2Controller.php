<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Lab5Controller;

class Lab9_2Controller extends Controller
{
    public function index()
    {
        return view('data_base.lab6.index');
    }

    public function generateImage()
    {
        $lab5 = new Lab5Controller();
        return $lab5->generateImage();
    }

    public function saveGeneratedImage(Request $request)
    {
        $request->validate([
            'image_data' => 'required|string',
        ]);

        $base64 = explode(',', $request->image_data)[1];

        Image::create(['data' => $base64]);

        return response()->json([
            'success' => true,
            'message' => 'Изображение сохранено!'
        ]);
    }

    public function getImages()
    {
        $images = Image::all();

        $formattedImages = $images->map(function($image) {
            return [
                'id' => $image->id,
                'created_at' => $image->created_at->format('d.m.Y H:i'),
                'size' => $this->getBase64ImageSize($image->data),
                'image' => $image->data
            ];
        });

        return response()->json($formattedImages);
    }

    private function getBase64ImageSize($base64)
    {
        try {
            $sizeInBytes = (int) (strlen(rtrim($base64, '=')) * 3 / 4);
            return round($sizeInBytes / 1024, 2) . ' KB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}