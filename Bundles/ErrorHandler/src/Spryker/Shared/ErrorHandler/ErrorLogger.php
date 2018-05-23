<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

use Exception;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\NewRelicApi\NewRelicApiTrait;
use Throwable;

class ErrorLogger implements ErrorLoggerInterface
{
    use LoggerTrait;
    use NewRelicApiTrait;

    /**
     * @var self
     */
    protected static $instance;

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorLogger
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * @param \Throwable $exception
     *
     * @return void
     */
    public function log($exception)
    {
        try {
            $message = $this->buildMessage($exception);
            $this->createNewRelicApi()->noticeError($message, $exception);
            $this->getLogger()->critical($message, ['exception' => $exception]);
        } catch (Throwable $internalException) {
            $this->createNewRelicApi()->noticeError($internalException->getMessage(), $exception);
        }
    }

    /**
     * @param \Throwable $exception
     *
     * @return string
     */
    protected function buildMessage($exception)
    {
        return sprintf(
            '%s - %s in "%s::%d"',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }
}
