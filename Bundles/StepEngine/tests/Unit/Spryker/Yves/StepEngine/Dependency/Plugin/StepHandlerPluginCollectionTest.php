<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine\Dependency\Plugin;

use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group StepEngine
 * @group Dependency
 * @group Plugin
 * @group StepHandlerPluginCollectionTest
 */
class StepHandlerPluginCollectionTest extends \PHPUnit_Framework_TestCase
{

    const TEST_PLUGIN_NAME = 'test';

    /**
     * @return void
     */
    public function testAdd()
    {
        $stepHandlerPluginCollection = new StepHandlerPluginCollection();

        $this->assertInstanceOf(
            StepHandlerPluginCollection::class,
            $stepHandlerPluginCollection->add($this->getStepHandlerPlugin(), self::TEST_PLUGIN_NAME)
        );
    }

    /**
     * @return void
     */
    public function testHasReturnFalse()
    {
        $stepHandlerPluginCollection = new StepHandlerPluginCollection();

        $this->assertFalse($stepHandlerPluginCollection->has(self::TEST_PLUGIN_NAME));
    }

    /**
     * @return void
     */
    public function testHasReturnTrue()
    {
        $stepHandlerPluginCollection = new StepHandlerPluginCollection();
        $stepHandlerPluginCollection->add($this->getStepHandlerPlugin(), self::TEST_PLUGIN_NAME);

        $this->assertTrue($stepHandlerPluginCollection->has(self::TEST_PLUGIN_NAME));
    }

    /**
     * @return void
     */
    public function testGet()
    {
        $stepHandlerPluginCollection = new StepHandlerPluginCollection();
        $stepHandlerPluginCollection->add($this->getStepHandlerPlugin(), self::TEST_PLUGIN_NAME);

        $this->assertInstanceOf(StepHandlerPluginInterface::class, $stepHandlerPluginCollection->get(self::TEST_PLUGIN_NAME));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface
     */
    private function getStepHandlerPlugin()
    {
        return $this->getMock(StepHandlerPluginInterface::class);
    }

}
