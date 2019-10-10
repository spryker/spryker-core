<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Event\Stub;

use Exception;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

class TestEventBulkListenerPluginStub implements EventBulkHandlerInterface
{
    /**
     * @var int
     */
    protected static $exceptionThrownCount = 0;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     * @param string $eventName
     *
     * @throws \Exception
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        if (static::$exceptionThrownCount < 2) {
            static::$exceptionThrownCount++;
            throw new Exception('Error during message handling');
        }
    }
}
