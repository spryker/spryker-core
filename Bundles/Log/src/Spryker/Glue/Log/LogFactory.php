<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\ProcessorInterface as MonologProcessorInterface;
use Monolog\Processor\PsrLogMessageProcessor;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\Log\Handler\QueueHandler;
use Spryker\Shared\Log\Processor\EnvironmentProcessor;
use Spryker\Shared\Log\Processor\GuzzleBodyProcessor;
use Spryker\Shared\Log\Processor\ProcessorInterface;
use Spryker\Shared\Log\Processor\RequestProcessor;
use Spryker\Shared\Log\Processor\ResponseProcessor;
use Spryker\Shared\Log\Processor\ServerProcessor;
use Spryker\Shared\Log\Sanitizer\Sanitizer;
use Spryker\Shared\Log\Sanitizer\SanitizerInterface;

/**
 * @method \Spryker\Glue\Log\LogConfig getConfig()
 */
class LogFactory extends AbstractFactory
{
    /**
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers(): array
    {
        return $this->getProvidedDependency(LogDependencyProvider::LOG_HANDLERS);
    }

    /**
     * @return callable[]
     */
    public function getProcessors(): array
    {
        return $this->getProvidedDependency(LogDependencyProvider::LOG_PROCESSORS);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createBufferedQueueHandler(): HandlerInterface
    {
        return new BufferHandler($this->createQueueHandler());
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createQueueHandler(): HandlerInterface
    {
        return new QueueHandler(
            $this->getProvidedDependency(LogDependencyProvider::CLIENT_QUEUE),
            $this->getConfig()->getQueueName()
        );
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createBufferedStreamHandler(): HandlerInterface
    {
        return new BufferHandler($this->createStreamHandler());
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createStreamHandler(): HandlerInterface
    {
        $streamHandler = new StreamHandler(
            $this->getConfig()->getLogFilePath(),
            $this->getConfig()->getLogLevel()
        );

        $streamHandler->setFormatter($this->createLogstashFormatter());

        return $streamHandler;
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface
     */
    public function createLogstashFormatter(): FormatterInterface
    {
        return new LogstashFormatter(APPLICATION);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createExceptionStreamHandler(): HandlerInterface
    {
        $streamHandler = new StreamHandler(
            $this->getConfig()->getExceptionLogFilePath(),
            Logger::ERROR
        );
        $streamHandler->setFormatter($this->createExceptionFormatter());

        return $streamHandler;
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface
     */
    public function createExceptionFormatter(): FormatterInterface
    {
        $lineFormatter = new LineFormatter();
        $lineFormatter->includeStacktraces(true);

        return $lineFormatter;
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createEnvironmentProcessor(): ProcessorInterface
    {
        return new EnvironmentProcessor();
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createGuzzleBodyProcessor(): ProcessorInterface
    {
        return new GuzzleBodyProcessor($this->createSanitizer());
    }

    /**
     * @return \Monolog\Processor\ProcessorInterface
     */
    public function createPsrMessageProcessor(): MonologProcessorInterface
    {
        return new PsrLogMessageProcessor();
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createRequestProcessor(): ProcessorInterface
    {
        return new RequestProcessor($this->createSanitizer());
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createResponseProcessor(): ProcessorInterface
    {
        return new ResponseProcessor();
    }

    /**
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createServerProcessor(): ProcessorInterface
    {
        return new ServerProcessor();
    }

    /**
     * @return \Spryker\Shared\Log\Sanitizer\SanitizerInterface
     */
    public function createSanitizer(): SanitizerInterface
    {
        return new Sanitizer(
            $this->getConfig()->getSanitizerFieldNames(),
            $this->getConfig()->getSanitizedFieldValue()
        );
    }
}
