<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log\Plugin\Handler;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Monolog\Handler\HandlerInterface;

/**
 * @method \Spryker\Glue\Log\LogFactory getFactory()
 */
class QueueHandlerPlugin extends AbstractHandlerPlugin
{
    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function getHandler(): HandlerInterface
    {
        if (!$this->handler) {
            $this->handler = $this->getFactory()->createBufferedQueueHandler();
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
