<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Setup\Business;

use Spryker\Zed\Setup\Business\SetupBusinessFactory;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Setup
 * @group Business
 * @group SetupBusinessFactoryTest
 */
class SetupBusinessFactoryTest extends \PHPUnit_Framework_TestCase
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
