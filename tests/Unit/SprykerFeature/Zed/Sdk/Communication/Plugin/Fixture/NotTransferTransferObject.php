<?php

namespace SprykerFeature\Shared\Foo\Sdk;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class NotTransferTransferObject extends AbstractTransfer
{
    private $foo = '';

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
 