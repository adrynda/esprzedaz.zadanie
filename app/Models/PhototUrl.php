<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhototUrl extends Model
{
    public const FILE_DIR = './pet/photos/'

    protected $table = 'photo_urls';

    public $timestamps = false;
}
