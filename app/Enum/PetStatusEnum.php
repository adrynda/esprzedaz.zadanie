<?php

namespace App\Enum;


enum PetStatusEnum: string
{
    case Available = 'available';

    case Pending = 'pending';

    case Sold = 'sold';
}
