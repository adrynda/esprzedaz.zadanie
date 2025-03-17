<?php

namespace App\DTOs;

class PetCustomUpdateDTO
{
    public function __construct(
        public readonly int $id,
        public readonly array $payload,
    ) {
    }
}
