<?php

namespace App\Http\Controllers\Api;

use App\Services\PetService;
use App\Validators\PetValidator;
use Illuminate\Http\Request;

class PetController
{
    public function __construct(
        private readonly PetService $petService,
    ) {
    }

    public function findByStatus(Request $request, PetValidator $petValidator)
    {
        return response()->json(
            $this->petService->findByStatus(
                $petValidator->validStatusQuery($request),
            ),
            200,
        );
    }

    public function store(Request $request, PetValidator $petValidator)
    {
        return response()->json(
            $this->petService->create(
                $petValidator->validPayload($request),
            ),
            201,
        );
    }

    public function show(Request $request, PetValidator $petValidator)
    {
        return response()->json(
            $this->petService->getById(
                $petValidator->validPetId($request),
            ),
            200,
        );
    }

    public function update(Request $request, PetValidator $petValidator)
    {
        return response()->json(
            $this->petService->update(
                $petValidator->validPayload($request),
            ),
            200,
        );
    }

    public function customUpdate(Request $request, PetValidator $petValidator)
    {
        //
    }

    public function destroy(Request $request, PetValidator $petValidator)
    {
        $this->petService->remove($petId);
        return response()->json(null, 204);
    }
}
