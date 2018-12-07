<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\Log\Handler\QueueHandler;
use Spryker\Shared\Log\Processor\EnvironmentProcessor;
use Spryker\Shared\Log\Processor\GuzzleBodyProcessor;
use Spryker\Shared\Log\Processor\RequestProcessor;
use Spryker\Shared\Log\Processor\ResponseProcessor;
use Spryker\Shared\Log\Processor\ServerProcessor;
use Spryker\Shared\Log\Sanitizer\Sanitizer;

/**
 * @method \Spryker\Glue\Log\LogConfig getConfig()
 */
class LogFactory extends AbstractFactory
{
    /**
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->getProvidedDependency(LogDependencyProvider::LOG_HANDLERS);
    }

    /**
     * @return callable[]
     */
    public function getProcessors()
    {
        return $this->getProvidedDependency(LogDependencyProvider::LOG_PROCESSORS);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createBufferedQueueHandler()
    {
        return new BufferHandler($this->createQueueHandler());
    }

    /**
     * @return \Monolog\Handler\HandlerInterface|\Spryker\Glue\Log\Handler\QueueHandler
     */
    public function createQueueHandler()
    {
        return new QueueHandler(
            $this->getProvidedDependency(LogDependencyProvider::CLIENT_QUEUE),
            $this->getConfig()->getQueueName()
        );
    }

    /**
     * @return \Monolog\Handler\HandlerInterface|\Monolog\Handler\BufferHandler
     */
    public function createBufferedStreamHandler()
    {
        return new BufferHandler($this->createStreamHandler());
    }

    /**
     * @return \Monolog\Handler\HandlerInterface|\Monolog\Handler\StreamHandler
     */
    public function createStreamHandler()
    {
        $streamHandler = new StreamHandler(
            $this->getConfig()->getLogFilePath(),
            $this->getConfig()->getLogLevel()
        );

        $streamHandler->setFormatter($this->createLogstashFormatter());

        return $streamHandler;
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface|\Monolog\Formatter\LogstashFormatter
     */
    public function createLogstashFormatter()
    {
        return new LogstashFormatter(APPLICATION);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface|\Monolog\Handler\FilterHandler
     */
    public function createExceptionStreamHandler()
    {
        $streamHandler = new StreamHandler(
            $this->getConfig()->getExceptionLogFilePath(),
            Logger::ERROR
        );
        $streamHandler->setFormatter($this->createExceptionFormatter());

        return $streamHandler;
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface|\Monolog\Formatter\LineFormatter
     */
    public function createExceptionFormatter()
    {
        $lineFormatter = new LineFormatter();
        $lineFormatter->includeStacktraces(true);

        return $lineFormatter;
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createEnvironmentProcessor()
    {
        return new EnvironmentProcessor();
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createGuzzleBodyProcessor()
    {
        return new GuzzleBodyProcessor($this->createSanitizer());
    }

    /**
     * @return \Monolog\Processor\PsrLogMessageProcessor
     */
    public function createPsrMessageProcessor()
    {
        return new PsrLogMessageProcessor();
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createRequestProcessor()
    {
        return new RequestProcessor($this->createSanitizer());
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createResponseProcessor()
    {
        return new ResponseProcessor();
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createServerProcessor()
    {
        return new ServerProcessor();
    }

    /**
     * @return \Spryker\Shared\Log\Sanitizer\SanitizerInterface
     */
    public function createSanitizer()
    {
        return new Sanitizer(
            $this->getConfig()->getSanitizerFieldNames(),
            $this->getConfig()->getSanitizedFieldValue()
        );
    }
}
