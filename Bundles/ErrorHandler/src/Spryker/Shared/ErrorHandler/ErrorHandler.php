<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

use ErrorException;
use Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Throwable;

class ErrorHandler
{
    public const ZED = 'ZED';
    public const EXIT_CODE_ERROR = -1;

    /**
     * @var \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    protected $errorLogger;

    /**
     * @var \Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface
     */
    protected $errorRenderer;

    /**
     * @param \Spryker\Shared\ErrorHandler\ErrorLoggerInterface $errorLogger
     * @param \Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface $errorRenderer
     */
    public function __construct(ErrorLoggerInterface $errorLogger, ErrorRendererInterface $errorRenderer)
    {
        $this->errorLogger = $errorLogger;
        $this->errorRenderer = $errorRenderer;
    }

    /**
     * @param \Exception|\Throwable $exception
     * @param bool $exit
     *
     * @return void
     */
    public function handleException($exception, $exit = true)
    {
        ErrorLogger::getInstance()->log($exception);

        try {
            $this->send500Header();
            $this->cleanOutputBuffer();
            echo $this->errorRenderer->render($exception);
        } catch (Throwable $internalException) {
            ErrorLogger::getInstance()->log($internalException);
        }

        if ($exit) {
            $this->sendExitCode();
        }
    }

    /**
     * @return void
     */
    public function handleFatal()
    {
        $error = $this->getLastError();
        if (!$error) {
            return;
        }

        $message = sprintf('FATAL ERROR - %s', $error['message']);
        $exception = new ErrorException($message, 0, $error['type'], $error['file'], $error['line']);
        $this->handleException($exception);
    }

    /**
     * @return array
     */
    protected function getLastError()
    {
        return error_get_last();
    }

    /**
     * @return void
     */
    protected function send500Header()
    {
        if (!headers_sent()) {
            header('HTTP/1.0 500 Internal Server Error');
        }
    }

    /**
     * @return void
     */
    protected function cleanOutputBuffer()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
    }

    /**
     * @return void
     */
    protected function sendExitCode()
    {
        exit(self::EXIT_CODE_ERROR);
    }
}
