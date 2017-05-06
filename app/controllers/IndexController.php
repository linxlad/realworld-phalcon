<?php

namespace RealWorld\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        return [
            'message' => 'You are now flying with Phalcon.',
        ];
    }
}

