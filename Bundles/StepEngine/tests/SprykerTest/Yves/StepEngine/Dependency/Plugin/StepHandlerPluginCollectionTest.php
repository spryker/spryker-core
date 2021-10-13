<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\StepEngine\Dependency\Plugin;

use Codeception\Test\Unit;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group StepEngine
 * @group Dependency
 * @group Plugin
 * @group StepHandlerPluginCollectionTest
 * Add your own group annotations below this line
 */
class StepHandlerPluginCollectionTest extends Unit
{
    /**
     * @var string
     */
    public const TEST_PLUGIN_NAME = 'test';

    /**
     * @return void
     */
    public function testAdd(): void
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
    public function testHasReturnFalse(): void
    {
        $stepHandlerPluginCollection = new StepHandlerPluginCollection();

        $this->assertFalse($stepHandlerPluginCollection->has(self::TEST_PLUGIN_NAME));
    }

    /**
     * @return void
     */
    public function testHasReturnTrue(): void
    {
        $stepHandlerPluginCollection = new StepHandlerPluginCollection();
        $stepHandlerPluginCollection->add($this->getStepHandlerPlugin(), self::TEST_PLUGIN_NAME);

        $this->assertTrue($stepHandlerPluginCollection->has(self::TEST_PLUGIN_NAME));
    }

    /**
     * @return void
     */
    public function testGet(): void
    {
        $stepHandlerPluginCollection = new StepHandlerPluginCollection();
        $stepHandlerPluginCollection->add($this->getStepHandlerPlugin(), self::TEST_PLUGIN_NAME);

        $this->assertInstanceOf(StepHandlerPluginInterface::class, $stepHandlerPluginCollection->get(self::TEST_PLUGIN_NAME));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface
     */
    private function getStepHandlerPlugin(): StepHandlerPluginInterface
    {
        return $this->getMockBuilder(StepHandlerPluginInterface::class)->getMock();
    }
}
