<?php

namespace TCGunel\YemeksepetiBot\Models;

class BaseModel
{
    public function __construct(?array $abstract)
    {
        foreach ($abstract as $key => $arg) {

            if (property_exists($this, $key)) {

                $this->$key = $arg;

            }

        }

        return $this;
    }
}
