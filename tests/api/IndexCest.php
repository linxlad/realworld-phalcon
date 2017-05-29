<?php


class IndexCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function checkRootResponse(ApiTester $I)
    {
        $I->sendGET('/');

    }
}
