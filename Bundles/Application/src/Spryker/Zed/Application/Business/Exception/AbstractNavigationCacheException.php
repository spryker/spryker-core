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

}
