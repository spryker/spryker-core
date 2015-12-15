<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Fixtures\ConsoleLocator\Console\Foo;

use Spryker\Zed\Console\Business\Model\Console;

class Bar extends Console
{

    const COMMAND_NAME = 'Bar';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);

        parent::configure();
    }

}
