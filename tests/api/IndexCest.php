<?php

class IndexCest
{
    public function indexDispatchedSendsCorrectMessage(UnitTester $I)
    {
        $I->amOnPage('/');
        $I->see('{"message":"You are now flying with Phalcon."}');
    }
}
