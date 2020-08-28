<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher\Business;

use Codeception\Test\Unit;
use Spryker\Shared\Publisher\PublisherConfig as SharedPublisherConfig;
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
    public function testGetPublisherPluginsMethodReturnsFlattedArrayWithDefinedQueueName(): void
    {
        // Arrange
        $this->setPublisherRegistryPlugins(SharedPublisherConfig::PUBLISH_QUEUE);

        // Act
        $publisherEvents = $this->tester->getFacade()->getPublisherEventCollection();

        // Assert
        $this->assertSame($this->getMergedPublisherEventsWithDefinedQueueName(), $publisherEvents);
    }

    /**
     * @return void
     */
    public function testGetPublisherPluginsMethodReturnsFlattedArrayWithDefaultQueueName(): void
    {
        // Arrange
        $this->setPublisherRegistryPlugins();

        // Act
        $publisherEvents = $this->tester->getFacade()->getPublisherEventCollection();

        // Assert
        $this->assertSame($this->getMergedPublisherEventsWithoutDefinedQueueName(), $publisherEvents);
    }

    /**
     * @param string|null $publishQueueName
     *
     * @return void
     */
    protected function setPublisherRegistryPlugins(?string $publishQueueName = null): void
    {
        $this->tester->mockConfigMethod('getPublishQueueName', function () use ($publishQueueName) {
            return $publishQueueName;
        });

        $this->tester->mockFactoryMethod('createPublisherEventCollator', new MockPublishEventCollator(
            $this->getPublisherRegistryPlugins(),
            $this->tester->getModuleConfig()
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
    protected function getMergedPublisherEventsWithDefinedQueueName(): array
    {
        return [
            SharedPublisherConfig::PUBLISH_QUEUE => [
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

    /**
     * @return array
     */
    protected function getMergedPublisherEventsWithoutDefinedQueueName(): array
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
