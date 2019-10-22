<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log\Plugin\Handler;

use Monolog\Formatter\FormatterInterface;
use Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Log\LogFactory getFactory()
 */
class ExceptionStreamHandlerPlugin extends AbstractPlugin implements LogHandlerPluginInterface
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
            $this->handler = $this->getFactory()->createExceptionStreamHandler();
        }

        return $this->handler;
    }

    /**
     * @param array $record
     *
     * @return bool
     */
    public function isHandling(array $record)
    {
        return $this->getHandler()->isHandling($record);
    }

    /**
     * @param array $record
     *
     * @return bool
     */
    public function handle(array $record)
    {
        return $this->getHandler()->handle($record);
    }

    /**
     * @param array $records
     *
     * @return mixed
     */
    public function handleBatch(array $records)
    {
        return $this->getHandler()->handleBatch($records);
    }

    /**
     * @param callable $callback
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    public function pushProcessor($callback)
    {
        return $this->getHandler()->pushProcessor($callback);
    }

    /**
     * @return callable
     */
    public function popProcessor()
    {
        return $this->getHandler()->popProcessor();
    }

    /**
     * @param \Monolog\Formatter\FormatterInterface $formatter
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        return $this->getHandler()->setFormatter($formatter);
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface
     */
    public function getFormatter()
    {
        return $this->getHandler()->getFormatter();
    }
}
