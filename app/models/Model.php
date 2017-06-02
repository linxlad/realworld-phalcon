<?php

namespace RealWorld\Models;

use InvalidArgumentException;
use function is_array;
use Phalcon\Mvc\Model as BaseModel;
use Phalcon\Text;
use Phalcon\Mvc\Model\MetaDataInterface;
use Phalcon\Db\AdapterInterface;

/**
 * Class Model
 * @package RealWorld\Models
 */
class Model extends BaseModel
{
    /**
     * @var bool
     */
    protected $exists = false;

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

    /**
     * Get one or more items randomly from the collection.
     *
     * @param  array     $items
     * @param  int|null  $amount
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function random($items, $amount = 1)
    {
        if (!is_array($items)) {
            throw new InvalidArgumentException("Parameter 1 must be of type array.");
        }

        if ($amount > ($count = count($items))) {
            throw new InvalidArgumentException("You requested {$amount} items, but there are only {$count} items in the array.");
        }

        $keys = array_rand($items, $amount);

        if (count(func_get_args()) == 0) {
            return $items[$keys];
        }

        $keys = !is_array($keys) ? [$keys] : $keys;

        return (array_intersect_key($items, array_flip($keys)));
    }
}