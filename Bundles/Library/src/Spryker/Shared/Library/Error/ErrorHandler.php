<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Error;

use ErrorException;
use Exception;
use Propel\Runtime\Propel;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Library\LibraryConstants;
use Throwable;

/**
 * @deprecated Use ErrorHandler bundle instead.
 */
class ErrorHandler
{

    /**
     * @var self
     */
    protected static $instance;

    const YVES = 'YVES';

    const ZED = 'ZED';

    const DEVELOPMENT = 'development';

    const SAPI_CLI = 'cli';

    /**
     * Do not allow object instantiation
     */
    private function __construct()
    {
    }

    /**
     * @return $this
     */
    public static function initialize()
    {
        if (!self::$instance) {
            self::$instance = new self();

            return self::$instance;
        }

        return self::$instance;
    }

    /**
     * @param \Exception|\Throwable $exception
     * @param bool $output
     * @param bool $exit
     *
     * @return void
     */
    public function handleException($exception, $output = true, $exit = true)
    {
        ErrorLogger::log($exception);

        try {
            $this->send500Header();

            $this->doDatabaseRollback();

            $this->cleanOutputBuffer();

            if ($this->showExceptionStackTrace()) {
                $this->echoOutput($exception, $output);
            } else {
                if ($exit) {
                    $this->showErrorPage();
                }
            }
        } catch (Throwable $internalException) {
            ErrorLogger::log($internalException);
        } catch (Exception $internalException) {
            ErrorLogger::log($internalException);
        }

        if ($exit) {
            exit(-1);
        }
    }

    /**
     * @return void
     */
    public function handleFatal()
    {
        $error = error_get_last();

        if (isset($error)) {
            $exception = new ErrorException('FATAL ERROR - ' . $error['message'], 0, $error['type'], $error['file'], $error['line']);
            $this->handleException($exception);
        }
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
     * @return bool
     */
    protected function showExceptionStackTrace()
    {
        if (APPLICATION === self::YVES) {
            return Config::get(LibraryConstants::YVES_SHOW_EXCEPTION_STACK_TRACE);
        }

        return Config::get(LibraryConstants::ZED_SHOW_EXCEPTION_STACK_TRACE);
    }

    /**
     * @param \Exception|\Throwable $exception
     * @param bool $output
     *
     * @return void
     */
    protected function echoOutput($exception, $output)
    {
        if ($output) {
            $message = ErrorRenderer::renderException($exception);

            echo $message;
        }
    }

    /**
     * @return void
     */
    protected function showErrorPage()
    {
        if (headers_sent() || $this->isCliCall()) {
            return;
        }

        $errorPage = Config::get(LibraryConstants::ZED_ERROR_PAGE);

        if (APPLICATION === self::YVES) {
            $errorPage = Config::get(LibraryConstants::YVES_ERROR_PAGE);
        }

        require_once $errorPage;
    }

    /**
     * @return void
     */
    protected function doDatabaseRollback()
    {
        if (APPLICATION === self::ZED && class_exists('Propel', false)) {
            Propel::getConnection()->forceRollBack();
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
     * @return bool
     */
    protected function isCliCall()
    {
        return PHP_SAPI === self::SAPI_CLI;
    }

}
