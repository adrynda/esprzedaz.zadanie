<?php

namespace App\Http\Controllers\Api;

use App\Services\PetService;
use App\Validators\PetValidator;
use Illuminate\Http\Request;

class PetController
{
    public function __construct(
        private readonly PetService $petService,
        private readonly Request $request,
        private readonly PetValidator $petValidator,
    ) {
    }

    public function findByStatus()
    {
        return response()->json(
            $this->petService->findByStatus(
                $this->petValidator->validFindByStatusQuery(),
            ),
            200,
        );
    }

    public function store()
    {
        return response()->json(
            $this->petService->create(
                $this->petValidator->validStore(),
            ),
            201,
        );
    }

    public function show()
    {
        return response()->json(
            $this->petService->getById(
                $this->petValidator->validShow(),
            ),
            200,
        );
    }

    public function update()
    {
        return response()->json(
            $this->petService->update(
                $this->petValidator->validUpdate(),
            ),
            200,
        );
    }

    public function customUpdate()
    {
        return response()->json(
            $this->petService->customUpdate(
                $this->petValidator->validCustomUpdate(),
            ),
            200,
        );
    }

    public function destroy()
    {
        $this->petService->remove(
            $this->petValidator->validDestroy(),
        );
        return response()->json(null, 204);
    }
}
