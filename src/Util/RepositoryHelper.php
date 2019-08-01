<?php

namespace App\Util;


trait RepositoryHelper
{

    /**
     * Set the entity parameters from request array
     *
     * @param $values
     */
    public function setAttributes($values)
    {
        $attributes = get_object_vars($this);
        foreach ($values as $key => $value) {
            if (array_key_exists($key, $attributes)) {
                $this->$key = $value;
            }
        }
    }

}