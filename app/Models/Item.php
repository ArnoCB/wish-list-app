<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
    public $id;

    /**
     * @var mixed|string
     */
    public $image;
    /**
     * @var mixed|string
     */

    public $api;
    /**
     * @var Item|mixed
     */
    public $price;
    /**
     * @var int|mixed
     */
    public $name;
    /**
     * @var false|mixed
     */

    public $wished;
}
