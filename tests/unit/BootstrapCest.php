<?php

use Phalcon\Config;
use Phalcon\Di;

class BootstrapCest
{
    /** @var  Di\FactoryDefault */
    private $diContainer;

    public function _before(UnitTester $I)
    {
        $this->diContainer = Di::getDefault();
    }

    public function _after(UnitTester $I)
    {
    }

    public function bootstrapReturnsCorrectConfig(UnitTester $I)
    {
        /** @var Config $config */
        $config = $this->diContainer->get('config');

        $I->assertTrue($config instanceof Config);
    }
}
