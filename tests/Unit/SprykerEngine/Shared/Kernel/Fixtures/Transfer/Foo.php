<?php

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures\Transfer;

use Unit\SprykerEngine\Shared\Kernel\Fixtures\Transfer\Foo\Bar;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

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
     * @return $this
     */
    public function setBar(Bar $bar)
    {
        $this->bar = $bar;

        return $this;
    }

}
