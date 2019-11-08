<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Publisher\Business\Registry\PublisherEventRegistry;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface;
use Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface;
use SprykerTest\Zed\Publisher\Business\Collator\MockPublishPluginCollator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Publisher
 * @group Business
 * @group Facade
 * @group PublisherFacadeTest
 * Add your own group annotations below this line
 */
class PublisherFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Publisher\PublisherBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetPublisherPluginsMethodReturnsFlattedArray(): void
    {
        $this->setPublisherRegistryPlugins();

        $publisherPlugins = $this->tester->getFacade()
            ->getPublisherPlugins();

        $this->assertSame($this->getMergedPublisherPlugins(), $publisherPlugins);
    }

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface
     */
    protected function expandPublisherEventRegistryOne(PublisherEventRegistryInterface $publisherEventRegistry): PublisherEventRegistryInterface
    {
        $publisherEventRegistry->register('TestEvent-1', 'TestPluginClassFoo');
        $publisherEventRegistry->register('TestEvent-2', 'TestPluginClassFoo');

        return $publisherEventRegistry;
    }

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface
     */
    protected function expandPublisherEventRegistryTwo(PublisherEventRegistryInterface $publisherEventRegistry): PublisherEventRegistryInterface
    {
        $publisherEventRegistry->register('TestEvent-2', 'TestPluginClassBar');
        $publisherEventRegistry->register('TestEvent-3', 'TestPluginClassBaz');

        return $publisherEventRegistry;
    }

    /**
     * @return void
     */
    protected function setPublisherRegistryPlugins(): void
    {
        $this->tester->mockFactoryMethod('createPublisherPluginCollator', new MockPublishPluginCollator(
            $this->getPublisherRegistryPlugins(),
            $this->createPublisherEventRegistry()
        ));
    }

    /**
     * @return array
     */
    protected function getPublisherRegistryPlugins(): array
    {
        $publisherRegistryPluginMockOne = $this->createMock(PublisherRegistryPluginInterface::class);
        $publisherRegistryPluginMockOne->method('expandPublisherEventRegistry')
            ->willReturnCallback(function (PublisherEventRegistryInterface $publisherEventRegistry) {
                return $this->expandPublisherEventRegistryOne($publisherEventRegistry);
            });

        $publisherRegistryPluginMockTwo = $this->createMock(PublisherRegistryPluginInterface::class);
        $publisherRegistryPluginMockTwo->method('expandPublisherEventRegistry')
            ->willReturnCallback(function (PublisherEventRegistryInterface $publisherEventRegistry) {
                return $this->expandPublisherEventRegistryTwo($publisherEventRegistry);
            });

        return [
            $publisherRegistryPluginMockOne,
            $publisherRegistryPluginMockTwo,
        ];
    }

    /**
     * @return \Spryker\Zed\Publisher\Business\Registry\PublisherEventRegistry
     */
    protected function createPublisherEventRegistry(): PublisherEventRegistry
    {
        return new PublisherEventRegistry();
    }

    /**
     * @return array
     */
    protected function getMergedPublisherPlugins(): array
    {
        return [
            'TestEvent-1' => [
                'TestPluginClassFoo',
            ],
            'TestEvent-2' => [
                'TestPluginClassFoo',
                'TestPluginClassBar',
            ],
            'TestEvent-3' => [
                'TestPluginClassBaz',
            ],
        ];
    }
}
