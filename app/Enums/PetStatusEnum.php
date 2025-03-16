<?php

namespace App\Enums;


enum PetStatusEnum: string
{
    case Available = 'available';

    case Pending = 'pending';

    case Sold = 'sold';
}
