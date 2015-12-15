<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\Exception;

use Spryker\Shared\Config;
use Spryker\Shared\Application\ApplicationConstants;

class Backtrace
{

    const CURRENT_PATH = '/data/shop/development/current';

    /**
     * @var string
     */
    private $backtrace;

    public function __construct()
    {
        $backtraceCollection = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($backtraceCollection as $backtrace) {
            $this->backtrace .= $this->getTraceLine($backtrace) . PHP_EOL;
        }
    }

    /**
     * @param $backtrace
     *
     * @return string
     */
    private function getTraceLine(array $backtrace)
    {
        if (isset($backtrace['file'])) {
            return $this->getUserFilePath($backtrace['file']) . ':' . $backtrace['line'];
        }

        return $this->getTraceLineFromTestCase($backtrace);
    }

    /**
     * @param array $backtrace
     *
     * @return string
     */
    private function getTraceLineFromTestCase(array $backtrace)
    {
        return $backtrace['class'] . $backtrace['type'] . $backtrace['function'];
    }

    /**
     * @param $backtraceFileName
     *
     * @return string
     */
    private function getUserFilePath($backtraceFileName)
    {
        $backtraceFileName = str_replace(
            self::CURRENT_PATH,
            Config::get(ApplicationConstants::BACKTRACE_USER_PATH, self::CURRENT_PATH),
            $backtraceFileName
        );

        return $backtraceFileName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->backtrace;
    }

}
