<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Publisher\Business\PublisherFacadeInterface;
use Spryker\Zed\Publisher\PublisherDependencyProvider;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface;
use Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface;

/**
 * Auto-generated group annotations
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

        $publisherPlugins = $this->getFacade()
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

        $this->tester->setDependency(PublisherDependencyProvider::PUBLISHER_REGISTRY_PLUGINS, [
            $publisherRegistryPluginMockOne,
            $publisherRegistryPluginMockTwo,
        ]);
    }

    /**
     * @return \Spryker\Zed\Publisher\Business\PublisherFacadeInterface
     */
    protected function getFacade(): PublisherFacadeInterface
    {
        /**
         * @var \Spryker\Zed\Publisher\Business\PublisherFacadeInterface $facade
         */
        $facade = $this->tester->getFacade();

        return $facade;
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
