<?php
namespace App\Traits;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait ImageUploadTrait
{
    // پارامتر دوم برای تعیین پوشه مقصد اضافه شد
    public function uploadImage($file, $path = 'uploads/general/')
    {
        $filename = time() . '.webp';
        $fullPath = public_path($path . $filename);

        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0755, true);
        }

        $manager = new ImageManager(new Driver());

        $image = $manager->read($file)
            ->scale(width: 1200)
            ->toWebp(quality: 80);

        $image->save($fullPath);

        return $path . $filename;
    }
}
