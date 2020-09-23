<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper\Testify;

use Codeception\Module;
use Codeception\Stub;

class ModuleHelper extends Module
{
    /**
     * @param string $className
     * @param array $methods
     *
     * @return \Codeception\RealInstanceType|object|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createHelperMock(string $className, array $methods)
    {
        return Stub::construct($className, ['moduleContainer' => $this->moduleContainer], $methods);
    }
}
