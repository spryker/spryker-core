<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher\Business;

use Codeception\Test\Unit;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;
use SprykerTest\Zed\Publisher\Business\Collator\MockPublishEventCollator;

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

        $publisherEvents = $this->tester->getFacade()
            ->getPublisherEventCollection();

        $this->assertSame($this->getMergedPublisherEvents(), $publisherEvents);
    }

    /**
     * @return void
     */
    protected function setPublisherRegistryPlugins(): void
    {
        $this->tester->mockFactoryMethod('createPublisherEventCollator', new MockPublishEventCollator(
            $this->getPublisherRegistryPlugins()
        ));
    }

    /**
     * @return array
     */
    protected function getPublisherRegistryPlugins(): array
    {
        $publisherRegistryPluginMockOne = $this->getMockBuilder(PublisherPluginInterface::class)
            ->setMockClassName('TestPluginClassFooPlugin')
            ->getMock();

        $publisherRegistryPluginMockOne
            ->method('getSubscribedEvents')
            ->willReturn([
                'TestEvent-1',
                'TestEvent-2',
            ]);

        $publisherRegistryPluginMockTwo = $this->getMockBuilder(PublisherPluginInterface::class)
            ->setMockClassName('TestPluginClassBarPlugin')
            ->getMock();

        $publisherRegistryPluginMockTwo
            ->method('getSubscribedEvents')
            ->willReturn([
                'TestEvent-2',
            ]);

        $publisherRegistryPluginMockThree = $this->getMockBuilder(PublisherPluginInterface::class)
            ->setMockClassName('TestPluginClassBazPlugin')
            ->getMock();

        $publisherRegistryPluginMockThree
            ->method('getSubscribedEvents')
            ->willReturn([
                'TestEvent-3',
            ]);

        return [
            $publisherRegistryPluginMockOne,
            $publisherRegistryPluginMockTwo,
            $publisherRegistryPluginMockThree,
        ];
    }

    /**
     * @return array
     */
    protected function getMergedPublisherEvents(): array
    {
        return [
            'event' => [
                'TestEvent-1' => [
                    'TestPluginClassFooPlugin',
                ],
                'TestEvent-2' => [
                    'TestPluginClassFooPlugin',
                    'TestPluginClassBarPlugin',
                ],
                'TestEvent-3' => [
                    'TestPluginClassBazPlugin',
                ],
            ],
        ];
    }
}
