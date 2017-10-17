<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorRenderer\CliErrorRenderer;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebHtmlErrorRenderer;

class ErrorHandlerFactory
{
    const APPLICATION_ZED = 'ZED';
    const SAPI_CLI = 'cli';
    const SAPI_PHPDBG = 'phpdbg';

    /**
     * @var string
     */
    protected $application;

    /**
     * @param string $application
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorHandler
     */
    public function createErrorHandler()
    {
        $errorLogger = $this->createErrorLogger();
        $errorRenderer = $this->createErrorRenderer();

        $errorHandler = new ErrorHandler($errorLogger, $errorRenderer);

        return $errorHandler;
    }

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorLogger
     */
    protected function createErrorLogger()
    {
        return new ErrorLogger();
    }

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface
     */
    protected function createErrorRenderer()
    {
        if ($this->isCliCall()) {
            return $this->createCliRenderer();
        }

        $errorRendererClassName = Config::get(ErrorHandlerConstants::ERROR_RENDERER, WebHtmlErrorRenderer::class);

        return $this->createWebErrorRenderer($errorRendererClassName);
    }

    /**
     * @return bool
     */
    protected function isCliCall()
    {
        return (PHP_SAPI === static::SAPI_CLI || PHP_SAPI === self::SAPI_PHPDBG);
    }

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorRenderer\CliErrorRenderer
     */
    protected function createCliRenderer()
    {
        return new CliErrorRenderer();
    }

    /**
     * @param string $errorRenderer
     *
     * @return \Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface
     */
    protected function createWebErrorRenderer($errorRenderer)
    {
        return new $errorRenderer($this->application);
    }
}
