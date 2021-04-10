<?php

namespace TCGunel\YemeksepetiBot\Models;

class Category extends BaseModel
{
    /** @var string */
    public $name;

    /** @var Product[] */
    public $products;

    public function __construct(array $abstract)
    {
        parent::__construct($abstract);
    }
}
