<?php

namespace TCGunel\YemeksepetiBot\Models;

class Product extends BaseModel
{
    /** @var string */
    public $id;

    /** @var string */
    public $title;

    /** @var string */
    public $description;

    /** @var float */
    public $normal_price;

    /** @var float */
    public $price;

    /** @var string */
    public $image;

    public function __construct(array $abstract)
    {
        parent::__construct($abstract);
    }
}
