<?php

namespace Unit\Spryker\Zed\Kernel\Communication\Plugin\Fixture;

use Spryker\Shared\Transfer\TransferInterface;

class NotGatewayController
{

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function badAction()
    {
        return 'bad';
    }

    /**
     * @param TransferInterface $foo
     * @param int $bar
     *
     * @return void
     */
    public function bazAction(TransferInterface $foo, $bar = 0)
    {
    }

}
