<?php

namespace App\Validators;

use App\Enums\PetStatusEnum;
use App\DTOs\PetCustomUpdateDTO;
use App\DTOs\PetUploadImageDTO;
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

    public function validFindByTagsQuery(): array
    {
        $tags = array_filter(
            explode(
                ',',
                $this->request->query()['tags'] ?? '',
            ),
        );

        $validator = Validator::make(
            [
                'tags' => $tags,
            ],
            [
                'tags.*' => 'required|string|exists:tags,name',
            ],
        );

        $this->validate($validator, 'Invalid tag value', 400);

        return $tags;
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

    public function validCustomUpdate(): PetCustomUpdateDTO
    {
        $petId = $this->validPetId();
        $payload = $this->readRequestData();

        $validator = Validator::make(
            $payload,
            [
                'name' => 'string',
                'status' => [Rule::in(PetStatusEnum::cases())],
            ],
        );

        $this->validate($validator, 'Invalid input', 405);

        return new PetCustomUpdateDTO($petId, $payload);
    }

    public function validUploadImage(): PetUploadImageDTO
    {
        $petId = $this->validPetId();
        $payload = $this->readRequestData();
        $files = $this->request->files->all();

        $validator = Validator::make(
            array_merge($payload, $files),
            [
                'additionalMetadata' => 'nullable|string',
                'file' => 'required|file',
            ],
        );

        $this->validate($validator, 'Invalid input', 405);

        return new PetUploadImageDTO(
            $petId,
            $payload['additionalMetadata'] ?? null,
            $files['file'],
        );
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
                'name' => 'required|string',
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
        if ($this->request->headers->get('content-type') == 'application/xml') {
            return json_decode(
                json_encode(
                    new SimpleXMLElement($this->request->getContent())
                ),
                true,
            );
        }
        return $this->request->getPayload()->all();
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
