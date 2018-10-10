<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Exception;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;

/**
 * Class will output a click able backtrace for php storm. To use this feature create config_local.php in config/Shared
 * and add `$config[KernelConstants::BACKTRACE_USER_PATH] = '/Users/your-name/www';`
 *
 * When you get a Kernel Exception, copy the backtrace and go to PhpStorm -> Tools -> Analyze Stacktrace
 */
class Backtrace
{
    public const CURRENT_PATH = '/data/shop/development/current';

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
     * @param array $backtrace
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
     * @param string $backtraceFile
     *
     * @return string
     */
    private function getUserFilePath($backtraceFile)
    {
        $backtraceFile = str_replace(
            self::CURRENT_PATH,
            Config::get(KernelConstants::BACKTRACE_USER_PATH, self::CURRENT_PATH),
            $backtraceFile
        );

        return $backtraceFile;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->backtrace;
    }
}
