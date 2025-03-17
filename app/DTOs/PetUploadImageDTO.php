<?php

namespace App\DTOs;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class PetUploadImageDTO
{
    public function __construct(
        public readonly int $petId,
        public readonly ?string $additionalMetadata,
        public readonly UploadedFile $file,
    ) {
    }
}
