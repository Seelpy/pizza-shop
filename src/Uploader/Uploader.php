<?php
declare(strict_types=1);

namespace App\Uploader;

class Uploader
{
    const UPLOAD_DIR = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';
    const UPLOAD_AVATAR_DIR = self::UPLOAD_DIR .  DIRECTORY_SEPARATOR . 'avatars';
    const UPLOAD_PIZZA_DIR = self::UPLOAD_DIR .  DIRECTORY_SEPARATOR . 'pizzas';
    const ALLOWED_IMAGE_TYPES = ['image/jpeg' => ".jpg", 'image/png' => ".png", 'image/gif' => ".gif"];

    public function moveAvatarToUploads(array $fileInfo): string
    {
        if (!$this->assertNormalFileType($fileInfo))
        {
            $type = $fileInfo["type"];
            throw new \RuntimeException("Invalid image type: $type");
        }
        return $this->moveFileToUploads($fileInfo, self::UPLOAD_AVATAR_DIR);
    }

    public function movePizzasImageToUploads(array $fileInfo): string
    {
        if (!$this->assertNormalFileType($fileInfo))
        {
            $type = $fileInfo["type"];
            throw new \RuntimeException("Invalid image type: $type");
        }
        return $this->moveFileToUploads($fileInfo, self::UPLOAD_PIZZA_DIR);
    }

    public function moveFileToUploads(array $fileInfo, string $uploadDir): string
    {
        $newName = uniqid("image", true) . self::ALLOWED_IMAGE_TYPES[$fileInfo['type']];
        $srcPath = $fileInfo['tmp_name']; 
        $destPath = $uploadDir. DIRECTORY_SEPARATOR . $newName;
        @move_uploaded_file($srcPath, $destPath);
        return $newName;
    }

    private function assertNormalFileType(array $fileInfo): bool
    {
        $type = $fileInfo['type'];
        return isset(self::ALLOWED_IMAGE_TYPES[$type]);
    }
}