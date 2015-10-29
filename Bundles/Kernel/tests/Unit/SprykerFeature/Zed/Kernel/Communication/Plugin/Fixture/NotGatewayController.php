<?php

namespace Unit\SprykerFeature\Zed\Kernel\Communication\Plugin\Fixture;

use SprykerEngine\Shared\Transfer\TransferInterface;

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
     */
    public function bazAction(TransferInterface $foo, $bar = 0)
    {
    }

}
