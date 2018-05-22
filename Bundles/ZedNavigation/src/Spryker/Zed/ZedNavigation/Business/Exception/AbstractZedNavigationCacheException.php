<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Exception;

use Exception;
use Spryker\Zed\ZedNavigation\Communication\Console\BuildNavigationConsole;

abstract class AbstractZedNavigationCacheException extends Exception
{
    const MESSAGE = 'To create a zed-navigation cache run:' . PHP_EOL
        . PHP_EOL . '$ vendor/bin/console ' . BuildNavigationConsole::COMMAND_NAME;

    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous = null
     */
    public function __construct($message = '', $code = 0, ?Exception $previous = null)
    {
        $message .= PHP_EOL . PHP_EOL . self::MESSAGE;

        parent::__construct($message, $code, $previous);
    }
}
