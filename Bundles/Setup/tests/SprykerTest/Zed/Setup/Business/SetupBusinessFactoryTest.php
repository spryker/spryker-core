<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Setup\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Setup\Business\SetupBusinessFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Setup
 * @group Business
 * @group SetupBusinessFactoryTest
 * Add your own group annotations below this line
 */
class SetupBusinessFactoryTest extends Unit
{
    /**
     * @return \Spryker\Zed\Setup\Business\SetupBusinessFactory
     */
    private function getFactory()
    {
        return new SetupBusinessFactory();
    }

    /**
     * @return void
     */
    public function testCreateModelCronjobsShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getFactory()->createModelCronjobs();

        $this->assertInstanceOf('Spryker\Zed\Setup\Business\Model\Cronjobs', $instance);
    }

    /**
     * @return void
     */
    public function testCreateModelGeneratedDirectoryRemoverShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getFactory()->createModelGeneratedDirectoryRemover();

        $this->assertInstanceOf('Spryker\Zed\Setup\Business\Model\DirectoryRemover', $instance);
    }
}
