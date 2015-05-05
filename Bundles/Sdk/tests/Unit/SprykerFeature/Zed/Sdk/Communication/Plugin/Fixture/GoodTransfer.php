<?php

namespace SprykerFeature\Shared\Sdk\Transfer;

use SprykerEngine\Shared\Transfer\AbstractTransfer;

class GoodTransfer extends AbstractTransfer
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
