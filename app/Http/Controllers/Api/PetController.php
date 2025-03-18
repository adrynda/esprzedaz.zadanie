<?php

namespace App\Http\Controllers\Api;

use App\Services\PetService;
use App\Services\PhotoUrlService;
use App\Validators\PetValidator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PetController
{
    public function __construct(
        private readonly PetService $petService,
        private readonly Request $request,
        private readonly PetValidator $petValidator,
    ) {
    }

    public function findByStatus(): Response
    {
        return $this->response(
            $this->petService->findByStatus(
                $this->petValidator->validFindByStatusQuery(),
            ),
            200,
        );
    }

    /**
     * @deprecated Takie oznaczenie widniało w dokumentacji
     */
    public function findByTags(): Response
    {
        return $this->response(
            $this->petService->findByTags(
                $this->petValidator->validFindByTagsQuery(),
            ),
            200,
        );
    }

    public function store(): Response
    {
        return $this->response(
            $this->petService->create(
                $this->petValidator->validStore(),
            ),
            201,
        );
    }

    public function show(): Response
    {
        return $this->response(
            $this->petService->getById(
                $this->petValidator->validShow(),
            ),
            200,
        );
    }

    public function update(): Response
    {
        return $this->response(
            $this->petService->update(
                $this->petValidator->validUpdate(),
            ),
            200,
        );
    }

    public function uploadImage(PhotoUrlService $photoUrlService): Response
    {
        return $this->response(
            $photoUrlService->uploadImage(
                $this->petValidator->validUploadImage(),
            ),
            200,
        );
    }

    public function customUpdate(): Response
    {
        return $this->response(
            $this->petService->customUpdate(
                $this->petValidator->validCustomUpdate(),
            ),
            200,
        );
    }

    public function destroy(): Response
    {
        $this->petService->remove(
            $this->petValidator->validDestroy(),
        );
        return $this->response();
    }

    private function response(
        mixed $responseData = null,
        int $statusCode = 204,
    ): Response {
        if ($this->request->headers->get('accept') === 'application/xml') {
            return response()->xml($responseData, $statusCode);
        }

        return response()->json($responseData, $statusCode);
    }
}
