<?php

namespace RealWorld\Models;

use Phalcon\Mvc\Model as BaseModel;
use Phalcon\Text;

/**
 * Class Model
 * @package RealWorld\Models
 */
class Model extends BaseModel
{
    /**
     * @return array
     */
    public function columnMap()
    {
        $columns = $this->getModelsMetaData()->getAttributes($this);
        $map = [];

        foreach ($columns as $column) {
            $map[$column] = lcfirst(Text::camelize($column));
        }

        return $map;
    }

    /**
     * @param array $input
     * @return $this
     */
    public function applyInputToModel(array $input)
    {
        foreach ($input as $field => $value) {
            $this->$field = $value;
        }

        return $this;
    }
}