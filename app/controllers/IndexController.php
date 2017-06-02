<?php

namespace RealWorld\Controllers;

use Phalcon\Mvc\Controller;
use Yarak\Yarak;

class IndexController extends Controller
{
    public function indexAction()
    {
        Yarak::call('migrate');
        Yarak::call('migrate:refresh');
        Yarak::call('db:seed');
        $this->response->setJsonContent(
            [
                'message' => 'You are now flying with Phalcon.',
            ]
        );
    }
}
