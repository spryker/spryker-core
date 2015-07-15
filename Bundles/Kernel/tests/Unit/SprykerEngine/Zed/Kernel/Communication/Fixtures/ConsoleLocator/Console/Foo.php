<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\ConsoleLocator\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;

class Foo extends Console
{

    const COMMAND_NAME = 'Foo';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);

        parent::configure();
    }

}
