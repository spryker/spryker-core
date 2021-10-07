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
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @throws \Exception
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        if (static::$exceptionThrownCount < 2) {
            static::$exceptionThrownCount++;

            throw new Exception('Error during message handling');
        }
    }
}
