<?php

namespace RealWorld\Repository;

use Phalcon\Mvc\Model\Query\BuilderInterface;

/**
 * Class Repository
 * @package RealWorld\Repository
 */
abstract class AbstractRepository
{
    /**
     * @param string $alias
     * @return BuilderInterface
     */
    public abstract function createNamedBuilder($alias);

    /**
     * @return string
     */
    public abstract function getModelName();
}