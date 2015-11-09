<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Error;

use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Shared\Yves\YvesConfig;

class ErrorHandler
{

    /**
     * @var ErrorHandler
     */
    protected static $instance;

    const YVES = 'YVES';

    const ZED = 'ZED';

    const DEVELOPMENT = 'development';

    /**
     * Do not allow object instantiation
     */
    private function __construct()
    {
    }

    /**
     * @return ErrorHandler
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
     * @param \Exception $exception
     * @param bool $output
     * @param bool $exit
     *
     * @return void
     */
    public function handleException(\Exception $exception, $output = true, $exit = true)
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
        } catch (\Exception $internalException) {
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
            $exception = new \ErrorException('FATAL ERROR - ' . $error['message'], 0, $error['type'], $error['file'], $error['line']);
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
            return Config::get(YvesConfig::YVES_SHOW_EXCEPTION_STACK_TRACE);
        }

        return Config::get(SystemConfig::ZED_SHOW_EXCEPTION_STACK_TRACE);
    }

    /**
     * @param \Exception $exception
     * @param $output
     *
     * @return void
     */
    protected function echoOutput(\Exception $exception, $output)
    {
        if ($output) {
            $message = ErrorRenderer::renderException($exception);
            echo $message;
        }
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected function showErrorPage()
    {
        if (headers_sent()) {
            return;
        }

        $errorPage = Config::get(SystemConfig::ZED_ERROR_PAGE);

        if (APPLICATION === self::YVES) {
            $errorPage = Config::get(YvesConfig::YVES_ERROR_PAGE);
        }

        require_once $errorPage;
    }

    /**
     * @return void
     */
    protected function doDatabaseRollback()
    {
        if (APPLICATION === self::ZED && class_exists('Propel', false)) {
            \Propel\Runtime\Propel::getConnection()->forceRollBack();
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

}
