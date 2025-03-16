<?php

namespace App\Validators;

use App\Enums\PetStatusEnum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use SimpleXMLElement;

class PetValidator
{
    public function validPetId(Request $request): int
    {
        $petId = $request->route()->parameters['petId'] ?? '';

        $this->validPetIdType($petId);
        $this->validPetIdExists($petId);

        return $petId;
    }

    public function validPayload(Request $request): array
    {
        $payload = $this->readRequestData($request);

        if ($request->getMethod() === 'PUT') {
            $this->validPetIdType($payload['id'] ?? '');
            $this->validPetIdExists($payload['id'] ?? '');
        }

        $validator = Validator::make(
            $payload,
            [
                'category.id' => 'required|int|exists:categories,id',
                'tags.*.id' => 'required|int|exists:tags,id',
                'status' => ['required', Rule::in(PetStatusEnum::cases())],
            ],
            [
                'tags.*.id.exists' => 'At least one selected tag does not exist.',
            ],
        );

        if ($validator->fails()) {
            throw new HttpResponseException(
                response()->json(
                    [
                        'message' => 'Invalid input',
                        'code' => 405,
                        'errors' => $validator->errors(),
                    ],
                    405,
                ),
            );
        }

        return $payload;
    }


    public function validStatusQuery(Request $request): int
    {
        $petId = $request->query()->parameters['petId'] ?? '';

        $this->validPetIdType($petId);
        $this->validPetIdExists($petId);

        return $petId;
    }

    private function validPetIdType(string $petId): void
    {
        $validator = Validator::make(
            [
                'petId' => $petId,
            ],
            [
                'petId' => 'required|int',
            ],
        );

        if ($validator->fails()) {
            throw new HttpResponseException(
                response()->json(
                    [
                        'message' => 'Invalid ID supplied',
                        'code' => 400,
                        'errors' => $validator->errors(),
                    ],
                    400,
                ),
            );
        }
    }

    private function validPetIdExists(string $petId): void
    {
        $validator = Validator::make(
            [
                'petId' => $petId,
            ],
            [
                'petId' => 'exists:pets,id',
            ],
        );

        if ($validator->fails()) {
            throw new HttpResponseException(
                response()->json(
                    [
                        'message' => 'Pet not found',
                        'code' => 404,
                        'errors' => $validator->errors(),
                    ],
                    400,
                ),
            );
        }
    }

    private function readRequestData(Request $request): array
    {
        return match ($request->headers->get('content-type')) {
            'application/json' => $request->getPayload()->all(),
            'application/xml' => json_decode(
                json_encode(
                    new SimpleXMLElement($request->getContent())
                ),
                true,
            ),
        };
    }
}
