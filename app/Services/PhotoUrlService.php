<?php

namespace App\Services;

use App\DTOs\PetUploadImageDTO;
use App\Models\Pet;
use App\Models\PhotoUrl;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoUrlService
{
    private const PHOTOS_DIRECTORY = 'pets/photos';

    public function uploadImage(PetUploadImageDTO $petUploadImageDTO): PhotoUrl
    {
        $photoUrl = new PhotoUrl();

        $photoUrl->pet_id = $petUploadImageDTO->petId;
        $photoUrl->name = $this->uploadFile($petUploadImageDTO);
        if (!empty($petUploadImageDTO->additionalMetadata)) {
            $photoUrl->additional_metadata = $petUploadImageDTO->additionalMetadata;
        }
        
        $photoUrl->save();
        
        return $photoUrl;
    }

    private function uploadFile(PetUploadImageDTO $petUploadImageDTO): string
    {
        $filename = sprintf(
            '%s_%s',
            $petUploadImageDTO->file->getBasename(),
            $petUploadImageDTO->file->getClientOriginalName(),
        );
        $destinationPath = self::PHOTOS_DIRECTORY . '/' . $petUploadImageDTO->petId;

        $petUploadImageDTO->file->move($destinationPath, $filename);

        return $destinationPath . '/' . $filename;
    }
}
