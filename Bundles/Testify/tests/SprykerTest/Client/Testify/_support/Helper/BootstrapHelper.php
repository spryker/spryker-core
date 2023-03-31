<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Testify\Helper;

use Codeception\Lib\Framework;
use Codeception\TestInterface;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\Container\GlobalContainer;

class BootstrapHelper extends Framework
{
    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        GlobalContainer::setContainer(new Container([]));
    }
}
