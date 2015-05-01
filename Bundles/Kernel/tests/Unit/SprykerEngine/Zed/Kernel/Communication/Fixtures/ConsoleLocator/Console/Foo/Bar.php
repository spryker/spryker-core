<?php

namespace Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\ConsoleLocator\Console\Foo;

use SprykerFeature\Zed\Console\Business\Model\Console;

class Bar extends Console
{

    const COMMAND_NAME = 'Bar';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);

        parent::configure();
    }

}
