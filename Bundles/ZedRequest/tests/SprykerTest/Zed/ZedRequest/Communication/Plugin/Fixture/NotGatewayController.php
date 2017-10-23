<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest\Communication\Plugin\Fixture;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $foo
     * @param int $bar
     *
     * @return int
     */
    public function bazAction(TransferInterface $foo, $bar = 0)
    {
        if ($foo) {
            $bar = 0;
        }

        return $bar;
    }
}
