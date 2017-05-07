<?php

namespace RealWorld\Controllers;

use Phalcon\Mvc\Controller;
use RealWorld\Models\Users;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->response->setJsonContent([
           'message' => 'You are now flying with Phalcon.'
        ]);

        return $this->response;
    }
}

