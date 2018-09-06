<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\DevelopmentFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group Facade
 * @group DevelopmentFacadeTest
 * Add your own group annotations below this line
 */
class DevelopmentFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindsProjectModules(): void
    {
        $moduleTransferCollection = $this->getFacade()->findProjectModules();

        $this->assertInternalType('array', $moduleTransferCollection);
    }

    /**
     * @return \Spryker\Zed\Development\Business\DevelopmentFacadeInterface
     */
    protected function getFacade(): DevelopmentFacadeInterface
    {
        return $this->tester->getFacade();
    }
}
