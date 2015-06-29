<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\Fixture;

class NotSdkController
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
 