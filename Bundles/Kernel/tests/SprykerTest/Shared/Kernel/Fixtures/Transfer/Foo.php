<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Shared\Kernel\Fixtures\Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerTest\Shared\Kernel\Fixtures\Transfer\Foo\Bar;

class Foo extends AbstractTransfer
{

    /**
     * @var mixed
     */
    protected $bar;

    /**
     * @return \SprykerTest\Shared\Kernel\Fixtures\Transfer\Foo\Bar
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param \SprykerTest\Shared\Kernel\Fixtures\Transfer\Foo\Bar $bar
     *
     * @return $this
     */
    public function setBar(Bar $bar)
    {
        $this->bar = $bar;

        return $this;
    }

}
