<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Fixtures\ConsoleLocator\Console;

use Spryker\Zed\Console\Business\Model\Console;

class Foo extends Console
{

    const COMMAND_NAME = 'Foo';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);

        parent::configure();
    }

}
