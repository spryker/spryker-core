<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Communication\Plugin\Handler;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Monolog\Handler\HandlerInterface;

/**
 * @method \Spryker\Zed\Log\Communication\LogCommunicationFactory getFactory()
 * @method \Spryker\Zed\Log\Business\LogFacadeInterface getFacade()
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 */
class QueueHandlerPlugin extends AbstractHandlerPlugin
{
    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function getHandler(): HandlerInterface
    {
        if (!$this->handler) {
            $this->handler = $this->getFactory()->createBufferedQueueHandlerPublic();
        }

        return $this->handler;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $record
     *
     * @return bool
     */
    public function isHandling(array $record): bool
    {
        if (!class_exists(QueueSendMessageTransfer::class)) {
            return false;
        }

        return parent::isHandling($record);
    }
}
