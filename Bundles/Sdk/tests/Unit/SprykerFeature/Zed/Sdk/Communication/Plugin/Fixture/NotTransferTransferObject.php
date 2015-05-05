<?php

namespace SprykerFeature\Shared\Foo\Sdk;

use SprykerEngine\Shared\Transfer\AbstractTransfer;

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
