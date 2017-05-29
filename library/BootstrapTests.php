<?php

namespace RealWorld;

use Phalcon\Mvc\Micro as PhMicro;

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
