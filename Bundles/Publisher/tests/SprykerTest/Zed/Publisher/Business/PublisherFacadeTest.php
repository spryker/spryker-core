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

        //Assert
        $this->assertArrayHasKey(SharedPublisherConfig::PUBLISH_QUEUE, $publisherEvents);
        $this->assertSame(
            ['TestEvent-1', 'TestEvent-2', 'TestEvent-3'],
            array_keys($publisherEvents[SharedPublisherConfig::PUBLISH_QUEUE]),
        );

        $this->assertCount(1, $publisherEvents[SharedPublisherConfig::PUBLISH_QUEUE]['TestEvent-1']);
        $this->assertCount(2, $publisherEvents[SharedPublisherConfig::PUBLISH_QUEUE]['TestEvent-2']);
        $this->assertCount(1, $publisherEvents[SharedPublisherConfig::PUBLISH_QUEUE]['TestEvent-3']);
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
        $this->assertArrayHasKey('event', $publisherEvents);
        $this->assertSame(
            ['TestEvent-1', 'TestEvent-2', 'TestEvent-3'],
            array_keys($publisherEvents['event']),
        );

        $this->assertCount(1, $publisherEvents['event']['TestEvent-1']);
        $this->assertCount(2, $publisherEvents['event']['TestEvent-2']);
        $this->assertCount(1, $publisherEvents['event']['TestEvent-3']);
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
            $this->tester->getModuleConfig(),
        ));
    }

    /**
     * @return array
     */
    protected function getPublisherRegistryPlugins(): array
    {
        $publisherRegistryPluginMockOne = $this->getMockForAbstractClass(
            PublisherPluginInterface::class,
        );

        $publisherRegistryPluginMockOne
            ->method('getSubscribedEvents')
            ->willReturn([
                'TestEvent-1',
                'TestEvent-2',
            ]);

        $publisherRegistryPluginMockTwo = $this->getMockForAbstractClass(
            PublisherPluginInterface::class,
        );

        $publisherRegistryPluginMockTwo
            ->method('getSubscribedEvents')
            ->willReturn([
                'TestEvent-2',
            ]);

        $publisherRegistryPluginMockThree = $this->getMockForAbstractClass(
            PublisherPluginInterface::class,
        );

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
}
