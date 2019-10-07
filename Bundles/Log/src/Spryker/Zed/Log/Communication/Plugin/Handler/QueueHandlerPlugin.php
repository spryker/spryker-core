<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Communication\Plugin\Handler;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Monolog\Formatter\FormatterInterface;
use Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Log\Communication\LogCommunicationFactory getFactory()
 * @method \Spryker\Zed\Log\Business\LogFacadeInterface getFacade()
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 */
class QueueHandlerPlugin extends AbstractPlugin implements LogHandlerPluginInterface
{
    /**
     * @var \Monolog\Handler\HandlerInterface|null
     */
    protected $handler;

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function getHandler()
    {
        if (!$this->handler) {
            $this->handler = $this->getFactory()->createBufferedQueueHandlerPublic();
        }

        return $this->handler;
    }

    /**
     * @api
     *
     * @param array $record
     *
     * @return bool
     */
    public function isHandling(array $record)
    {
        if (!class_exists(QueueSendMessageTransfer::class)) {
            return false;
        }

        return $this->getHandler()->isHandling($record);
    }

    /**
     * @api
     *
     * @param array $record
     *
     * @return bool
     */
    public function handle(array $record)
    {
        return $this->getHandler()->handle($record);
    }

    /**
     * @api
     *
     * @param array $records
     *
     * @return mixed
     */
    public function handleBatch(array $records)
    {
        return $this->getHandler()->handleBatch($records);
    }

    /**
     * @api
     *
     * @param callable $callback
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    public function pushProcessor($callback)
    {
        return $this->getHandler()->pushProcessor($callback);
    }

    /**
     * @api
     *
     * @return callable
     */
    public function popProcessor()
    {
        return $this->getHandler()->popProcessor();
    }

    /**
     * @api
     *
     * @param \Monolog\Formatter\FormatterInterface $formatter
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        return $this->getHandler()->setFormatter($formatter);
    }

    /**
     * @api
     *
     * @return \Monolog\Formatter\FormatterInterface
     */
    public function getFormatter()
    {
        return $this->getHandler()->getFormatter();
    }
}
