<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper;

use Codeception\Lib\Framework;
use Codeception\TestInterface;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Zed\Kernel\Container;

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
