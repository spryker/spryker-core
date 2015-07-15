<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Error;

use SprykerFeature\Shared\Library\Config;
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
            set_exception_handler([self::$instance, 'handleException']);
            register_shutdown_function([self::$instance, 'handleFatal']);

            return self::$instance;
        }

        return self::$instance;
    }

    public function handleException(\Exception $exception, $output = true, $exit = true)
    {
        ErrorLogger::log($exception);

        try {
            $this->send500Header();

            $this->doDatabaseRollback();

            $this->cleanOutputBuffer();

            if ($this->isEchoOutput()) {
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

    public function handleFatal()
    {
        $error = error_get_last();

        if (isset($error)) {
            $exception = new \ErrorException('FATAL ERROR - ' . $error['message'], 0, $error['type'], $error['file'], $error['line']);
            $this->handleException($exception);
        }
    }

    protected function send500Header()
    {
        if (!headers_sent()) {
            header('HTTP/1.0 500 Internal Server Error');
        }
    }

    /**
     * @return bool
     */
    protected function isEchoOutput()
    {
        return (APPLICATION === self::YVES && APPLICATION_ENV === self::DEVELOPMENT)
        || APPLICATION === self::ZED;
    }

    /**
     * @param \Exception $exception
     * @param $output
     */
    protected function echoOutput(\Exception $exception, $output)
    {
        if ($output) {
            $message = ErrorRenderer::renderException($exception);
            echo $message;
        }
    }

    protected function showErrorPage()
    {
        if (!headers_sent()) {
            $errorPage = Config::get(YvesConfig::YVES_ERROR_PAGE);
            require_once $errorPage;
        }
    }

    protected function doDatabaseRollback()
    {
        if (APPLICATION === self::ZED && class_exists('Propel', false)) {
            \Propel\Runtime\Propel::getConnection()->forceRollBack();
        }
    }

    protected function cleanOutputBuffer()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
    }

}
