<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Client\Communication\Plugin\Fixture;

class NotClientController
{
    public function __construct()
    {

    }

    public function fooAction()
    {
        return "foo";
    }



    public function bazAction(FooTransfer $foo, $bar = 0)
    {

    }
}
