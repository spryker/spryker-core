<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Kernel\Fixtures\Transfer;

use Unit\Spryker\Shared\Kernel\Fixtures\Transfer\Foo\Bar;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class Foo extends AbstractTransfer
{

    /**
     * @var mixed
     */
    protected $bar;

    /**
     * @return Bar
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param Bar $bar
     *
     * @return self
     */
    public function setBar(Bar $bar)
    {
        $this->bar = $bar;

        return $this;
    }

}
