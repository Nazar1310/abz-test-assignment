<?php
namespace App\Services;

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use function Tinify\fromFile;
use function Tinify\setKey;

class ImageService
{
    /**
     * @param UploadedFile $photo
     * @param bool $optimize
     * @return string|null
     */
    public function saveUploadedImage(UploadedFile $photo, bool $optimize = false): ?string
    {
        try {
            $extension = $photo->getClientOriginalExtension();
            $image = Image::make($photo->getRealPath())
                ->fit(70, 70, function ($constraint) {
                    $constraint->upsize();
                });

            $photoName = time() . '.' . $extension;
            $photoPath = 'public/uploads/' . $photoName;
            Storage::put($photoPath, (string) $image->encode());

            if ($optimize && !$this->optimize($photoPath)) {
                Log::warning("Image optimization failed for: $photoPath");
            }

            return Storage::url('uploads/' . $photoName);
        } catch (Exception $e) {
            Log::error('Failed to save uploaded image: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * @param string $photoPath
     * @return bool
     */
    public function optimize(string $photoPath): bool
    {
        try {
            setKey(env('TINYPNG_API_KEY'));
            $localPath = Storage::path($photoPath);
            $source = fromFile($localPath);
            $source->toFile($localPath);

            return true;
        } catch (Exception $e) {
            Log::error('Image optimization failed: ' . $e->getMessage());
        }

        return false;
    }
}

