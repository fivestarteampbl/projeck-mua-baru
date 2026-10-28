<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FotoStorage
{
    private const DISK = 'public';

    public function simpan(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, self::DISK);
    }

    public function ganti(?string $pathLama, UploadedFile $file, string $folder): string
    {
        $pathBaru = $this->simpan($file, $folder);
        $this->hapus($pathLama);

        return $pathBaru;
    }

    public function hapus(?string $path): void
    {
        if ($path !== null) {
            Storage::disk(self::DISK)->delete($path);
        }
    }
}
