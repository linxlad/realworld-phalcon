<?php

namespace RealWorld\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->response->setJsonContent(
            [
                'message' => 'You are now flying with Phalcon.',
            ]
        );
    }
}
