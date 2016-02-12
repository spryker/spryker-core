<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Exception;

use Spryker\Zed\Application\Communication\Console\BuildNavigationConsole;

abstract class AbstractNavigationCacheException extends \Exception
{

    const MESSAGE = 'To create a navigation cache run:' . PHP_EOL
        . PHP_EOL . '$ vendor/bin/console ' . BuildNavigationConsole::COMMAND_NAME;

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous = null
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        $message .= PHP_EOL . PHP_EOL . self::MESSAGE;

        parent::__construct($message, $code, $previous);
    }

}
