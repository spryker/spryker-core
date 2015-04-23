<?php

namespace Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\Fixture;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

/**
 * This has to be here cause of a namespace check which is part of
 * Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\SdkControllerListenerPluginTest
 */
class FooTransfer extends AbstractTransfer
{
    protected $foo = 'bar';

    /**
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string $foo
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;
    }
}
 