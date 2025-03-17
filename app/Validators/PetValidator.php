<?php

namespace App\Validators;

use App\Enums\PetStatusEnum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as MadeValidator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use SimpleXMLElement;

class PetValidator
{
    public function __construct(
        private readonly Request $request,
    ) {
    }

    public function validFindByStatusQuery(): PetStatusEnum
    {
        $query = $this->request->query();

        $validator = Validator::make(
            $query,
            [
                'status' => ['required', Rule::in(PetStatusEnum::cases())],
            ],
        );

        $this->validate($validator, 'Invalid status value', 400);

        return PetStatusEnum::from($query['status']);
    }

    public function validStore(): array
    {
        return $this->validPayload();
    }

    public function validUpdate(): array
    {
        return $this->validPayload(true);
    }

    public function validShow(): int
    {
        return $this->validPetId();
    }

    public function validDestroy(): int
    {
        return $this->validPetId();
    }

    private function validPetId(): int
    {
        $petId = $this->request->route()->parameters['petId'] ?? '';

        $this->validPetIdType($petId);
        $this->validPetIdExists($petId);

        return $petId;
    }

    private function validPayload(bool $validateId = false): array
    {
        $payload = $this->readRequestData();

        if ($validateId) {
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

        $this->validate($validator, 'Invalid input', 405);

        return $payload;
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

        $this->validate($validator, 'Invalid ID supplied', 400);
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

        $this->validate($validator, 'Pet not found', 404);
    }

    private function readRequestData(): array
    {
        return match ($this->request->headers->get('content-type')) {
            'application/json' => $this->request->getPayload()->all(),
            'application/xml' => json_decode(
                json_encode(
                    new SimpleXMLElement($this->request->getContent())
                ),
                true,
            ),
        };
    }

    private function validate(
        MadeValidator $validator,
        string $message,
        int $statusCode,
    ): void
    {
        if ($validator->fails()) {
            throw new HttpResponseException(
                response()->json(
                    [
                        'message' => $message,
                        'code' => $statusCode,
                        'errors' => $validator->errors(),
                    ],
                    $statusCode,
                ),
            );
        }
    }
}
