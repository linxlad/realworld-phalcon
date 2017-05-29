<?php

namespace RealWorld;

use Phalcon\Mvc\Micro as PhMicro;

/**
 * Class BootstrapTests
 * @package RealWorld
 */
class BootstrapTests extends Bootstrap
{
    /**
     * @return PhMicro
     */
    protected function runApplication()
    {
        return $this->application;
    }
}
