<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Communication;

use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Spryker\Shared\Log\Processor\EnvironmentProcessor;
use Spryker\Shared\Log\Processor\GuzzleBodyProcessor;
use Spryker\Shared\Log\Processor\RequestProcessor;
use Spryker\Shared\Log\Processor\ResponseProcessor;
use Spryker\Shared\Log\Processor\ServerProcessor;
use Spryker\Shared\Log\Sanitizer\Sanitizer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Log\Communication\Handler\QueueHandler;
use Spryker\Zed\Log\LogDependencyProvider;

/**
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 */
class LogCommunicationFactory extends AbstractCommunicationFactory
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
     * @deprecated Use createEnvironmentProcessorPublic() instead
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    protected function createEnvironmentProcessor()
    {
        return $this->createEnvironmentProcessorPublic();
    }

    /**
     * Deprecated: Will be renamed to createEnvironmentProcessor() in the next major release
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createEnvironmentProcessorPublic()
    {
        return new EnvironmentProcessor();
    }

    /**
     * @deprecated Use createRequestProcessorPublic() instead
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    protected function createRequestProcessor()
    {
        return $this->createRequestProcessorPublic();
    }

    /**
     * Deprecated: Will be renamed to createRequestProcessor() in the next major release
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createRequestProcessorPublic()
    {
        return new RequestProcessor($this->createSanitizer());
    }

    /**
     * @deprecated Use createResponseProcessorPublic() instead
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    protected function createResponseProcessor()
    {
        return $this->createResponseProcessorPublic();
    }

    /**
     * Deprecated: Will be renamed to createResponseProcessor() in the next major release
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createResponseProcessorPublic()
    {
        return new ResponseProcessor();
    }

    /**
     * @deprecated Use createServerProcessorPublic() instead
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    protected function createServerProcessor()
    {
        return $this->createServerProcessorPublic();
    }

    /**
     * Deprecated: Will be renamed to createServerProcessorPublic() in the next major release
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createServerProcessorPublic()
    {
        return new ServerProcessor();
    }

    /**
     * @deprecated Use createGuzzleBodyProcessorPublic() instead
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    protected function createGuzzleBodyProcessor()
    {
        return new GuzzleBodyProcessor($this->createSanitizer());
    }

    /**
     * Deprecated: Will be renamed to createGuzzleBodyProcessor() in the next major release
     *
     * @return \Spryker\Shared\Log\Processor\ProcessorInterface
     */
    public function createGuzzleBodyProcessorPublic()
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
     * @return \Spryker\Shared\Log\Sanitizer\SanitizerInterface
     */
    protected function createSanitizer()
    {
        return new Sanitizer(
            $this->getConfig()->getSanitizerFieldNames(),
            $this->getConfig()->getSanitizedFieldValue()
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
    protected function createStreamHandler()
    {
        $streamHandler = new StreamHandler(
            $this->getConfig()->getLogFilePath(),
            (int)$this->getConfig()->getLogLevel()
        );

        $streamHandler->setFormatter($this->createLogstashFormatter());

        return $streamHandler;
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface|\Monolog\Formatter\LogstashFormatter
     */
    protected function createLogstashFormatter()
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
    protected function createExceptionFormatter()
    {
        $lineFormatter = new LineFormatter();
        $lineFormatter->includeStacktraces(true);

        return $lineFormatter;
    }

    /**
     * @deprecated Use createBufferedQueueHandlerPublic() instead
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createBufferedQueueHandler()
    {
        return $this->createBufferedQueueHandlerPublic();
    }

    /**
     * Deprecated: Will be renamed to createBufferedQueueHandlerPublic()
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createBufferedQueueHandlerPublic()
    {
        return new BufferHandler($this->createQueueHandler());
    }

    /**
     * @return \Monolog\Handler\HandlerInterface|\Spryker\Zed\Log\Communication\Handler\QueueHandler
     */
    protected function createQueueHandler()
    {
        return new QueueHandler(
            $this->getProvidedDependency(LogDependencyProvider::CLIENT_QUEUE),
            $this->getConfig()->getQueueName()
        );
    }
}
