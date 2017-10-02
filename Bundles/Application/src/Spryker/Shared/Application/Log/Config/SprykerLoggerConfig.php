<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Log\Config;

use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Spryker\Shared\Application\Log\Processor\EntitySanitizerProcessor;
use Spryker\Shared\Application\Log\Processor\EnvironmentProcessor;
use Spryker\Shared\Application\Log\Processor\GuzzleBodyProcessor;
use Spryker\Shared\Application\Log\Processor\RequestProcessor;
use Spryker\Shared\Application\Log\Processor\ResponseProcessor;
use Spryker\Shared\Application\Log\Processor\ServerProcessor;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Log\Config\LoggerConfigInterface;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Log\Sanitizer\Sanitizer;

class SprykerLoggerConfig implements LoggerConfigInterface
{
    /**
     * @return string
     */
    public function getChannelName()
    {
        return 'Spryker';
    }

    /**
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers()
    {
        $handler = [
            $this->createStreamHandler(),
        ];

        if (Config::hasKey(LogConstants::EXCEPTION_LOG_FILE_PATH)) {
            $handler[] = $this->createExceptionHandler();
        }

        return $handler;
    }

    /**
     * @return callable[]
     */
    public function getProcessors()
    {
        $sanitizer = new Sanitizer(
            Config::get(LogConstants::LOG_SANITIZE_FIELDS, []),
            Config::get(LogConstants::LOG_SANITIZED_VALUE, '***')
        );

        return [
            new PsrLogMessageProcessor(),
            new EntitySanitizerProcessor($sanitizer),
            new EnvironmentProcessor(),
            new ServerProcessor(),
            new RequestProcessor($sanitizer),
            new ResponseProcessor(),
            new GuzzleBodyProcessor($sanitizer),
        ];
    }

    /**
     * @return \Monolog\Handler\StreamHandler
     */
    protected function createStreamHandler()
    {
        $streamHandler = new StreamHandler(
            Config::get(LogConstants::LOG_FILE_PATH),
            Config::get(LogConstants::LOG_LEVEL, Logger::INFO)
        );
        $formatter = new LogstashFormatter('Spryker');
        $streamHandler->setFormatter($formatter);

        return $streamHandler;
    }

    /**
     * @return \Monolog\Handler\FilterHandler
     */
    protected function createExceptionHandler()
    {
        $lineFormatter = new LineFormatter();
        $lineFormatter->includeStacktraces(true);

        $streamHandler = new StreamHandler(Config::get(LogConstants::EXCEPTION_LOG_FILE_PATH));
        $streamHandler->setFormatter($lineFormatter);

        $filterHandler = new FilterHandler($streamHandler, Logger::ERROR);

        return $filterHandler;
    }
}
