<?php

namespace RealWorld\Controllers;

use Phalcon\Mvc\Controller;
use Yarak\Yarak;

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

    public function seedAction()
    {
        Yarak::call('migrate:refresh');
        Yarak::call('db:seed');

        $this->response->setJsonContent(
            [
                'message' => 'Database seeded.',
            ]
        );
    }
}
