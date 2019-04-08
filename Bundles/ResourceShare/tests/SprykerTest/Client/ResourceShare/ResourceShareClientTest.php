<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ResourceShare;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ResourceShareBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\ResourceShare\ResourceShareDependencyProvider;
use Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ResourceShare
 * @group ResourceShareClientTest
 * Add your own group annotations below this line
 */
class ResourceShareClientTest extends Unit
{
    protected const KEY_RESOURCE_DATA = 'resource_data';
    protected const VALUE_RESOURCE_DATA = 'VALUE_RESOURCE_DATA';
    protected const VALUE_RESOURCE_DATA_REPLACED = 'VALUE_RESOURCE_DATA_REPLACED';
    protected const VALUE_RESOURCE_DATA_EXPANDED = '_EXPANDED';
    protected const VALUE_RESOURCE_SHARE_UUID = 'VALUE_RESOURCE_SHARE_UUID';

    /**
     * @var \SprykerTest\Client\ResourceShare\ResourceShareClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateResourceShareCanExpandResourceDataUsingPlugins(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();
        $resourceShareTransfer->setResourceData(static::VALUE_RESOURCE_DATA);

        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginWhichExpandsResourceDataMock();
        $this->registerResourceShareResourceDataExpanderStrategyPlugin($resourceShareResourceDataExpanderStrategyPluginMock);

        // Act
        $resourceShareResponseTransfer = $this->getClient()->generateResourceShare($resourceShareTransfer);

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertSame(
            $resourceShareResponseTransfer->getResourceShare()->getResourceData(),
            static::VALUE_RESOURCE_DATA . static::VALUE_RESOURCE_DATA_EXPANDED
        );
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareCanReplaceResourceDataUsingPlugins(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();
        $resourceShareTransfer->setResourceData(static::VALUE_RESOURCE_DATA);

        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginWhichReplacesResourceDataMock();
        $this->registerResourceShareResourceDataExpanderStrategyPlugin($resourceShareResourceDataExpanderStrategyPluginMock);

        // Act
        $resourceShareResponseTransfer = $this->getClient()->generateResourceShare($resourceShareTransfer);

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertSame(
            $resourceShareResponseTransfer->getResourceShare()->getResourceData(),
            static::VALUE_RESOURCE_DATA_REPLACED
        );
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareWillNotExpandResourceDataIfGenerationFailed(): void
    {
        // Arrange
        $resourceShareTransfer = new ResourceShareTransfer();
        $resourceShareTransfer->setResourceData(static::VALUE_RESOURCE_DATA);

        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginWhichExpandsResourceDataMock();
        $this->registerResourceShareResourceDataExpanderStrategyPlugin($resourceShareResourceDataExpanderStrategyPluginMock);

        // Act
        $resourceShareResponseTransfer = $this->getClient()->generateResourceShare($resourceShareTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNull($resourceShareResponseTransfer->getResourceShare());
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareWillNotReplaceResourceDataIfGenerationFailed(): void
    {
        // Arrange
        $resourceShareTransfer = new ResourceShareTransfer();
        $resourceShareTransfer->setResourceData(static::VALUE_RESOURCE_DATA);

        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginWhichReplacesResourceDataMock();
        $this->registerResourceShareResourceDataExpanderStrategyPlugin($resourceShareResourceDataExpanderStrategyPluginMock);

        // Act
        $resourceShareResponseTransfer = $this->getClient()->generateResourceShare($resourceShareTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNull($resourceShareResponseTransfer->getResourceShare());
    }

    /**
     * @return void
     */
    public function testActivateResourceShareCanExpandResourceDataUsingPlugins(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();
        $resourceShareTransfer->setResourceData(static::VALUE_RESOURCE_DATA);
        $resourceShareTransfer = $this->getClient()->generateResourceShare($resourceShareTransfer)->getResourceShare();

        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginWhichExpandsResourceDataMock();
        $this->registerResourceShareResourceDataExpanderStrategyPlugin($resourceShareResourceDataExpanderStrategyPluginMock);

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($resourceShareTransfer->getCustomerReference())
            ->setIsGuest(false);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setUuid($resourceShareTransfer->getUuid())
            ->setCustomer($customerTransfer);

        // Act
        $resourceShareResponseTransfer = $this->getClient()->activateResourceShare($resourceShareRequestTransfer);

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertSame(
            $resourceShareResponseTransfer->getResourceShare()->getResourceData(),
            static::VALUE_RESOURCE_DATA . static::VALUE_RESOURCE_DATA_EXPANDED
        );
    }

    /**
     * @return void
     */
    public function testActivateResourceShareCanReplaceResourceDataUsingPlugins(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareBuilder())->build();
        $resourceShareTransfer->setResourceData(static::VALUE_RESOURCE_DATA);
        $resourceShareTransfer = $this->getClient()->generateResourceShare($resourceShareTransfer)->getResourceShare();

        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginWhichReplacesResourceDataMock();
        $this->registerResourceShareResourceDataExpanderStrategyPlugin($resourceShareResourceDataExpanderStrategyPluginMock);

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($resourceShareTransfer->getCustomerReference())
            ->setIsGuest(false);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setUuid($resourceShareTransfer->getUuid())
            ->setCustomer($customerTransfer);

        // Act
        $resourceShareResponseTransfer = $this->getClient()->activateResourceShare($resourceShareRequestTransfer);

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertSame(
            $resourceShareResponseTransfer->getResourceShare()->getResourceData(),
            static::VALUE_RESOURCE_DATA_REPLACED
        );
    }

    /**
     * @return void
     */
    public function testActivateResourceShareWillNotExpandResourceDataIfGenerationFailed(): void
    {
        // Arrange
        $resourceShareTransfer = new ResourceShareTransfer();
        $resourceShareTransfer->setResourceData(static::VALUE_RESOURCE_DATA);

        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginWhichExpandsResourceDataMock();
        $this->registerResourceShareResourceDataExpanderStrategyPlugin($resourceShareResourceDataExpanderStrategyPluginMock);

        // Act
        $resourceShareResponseTransfer = $this->getClient()->generateResourceShare($resourceShareTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNull($resourceShareResponseTransfer->getResourceShare());
    }

    /**
     * @return void
     */
    public function testActivateResourceShareWillNotReplaceResourceDataIfGenerationFailed(): void
    {
        // Arrange
        $resourceShareTransfer = new ResourceShareTransfer();
        $resourceShareTransfer->setResourceData(static::VALUE_RESOURCE_DATA);

        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginWhichReplacesResourceDataMock();
        $this->registerResourceShareResourceDataExpanderStrategyPlugin($resourceShareResourceDataExpanderStrategyPluginMock);

        // Act
        $resourceShareResponseTransfer = $this->getClient()->generateResourceShare($resourceShareTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNull($resourceShareResponseTransfer->getResourceShare());
    }

    /**
     * @param \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface $resourceShareResourceDataExpanderStrategyPlugin
     *
     * @return void
     */
    protected function registerResourceShareResourceDataExpanderStrategyPlugin(
        ResourceShareResourceDataExpanderStrategyPluginInterface $resourceShareResourceDataExpanderStrategyPlugin
    ): void {
        $this->tester->setDependency(
            ResourceShareDependencyProvider::PLUGINS_RESOURCE_SHARE_RESOURCE_DATA_EXPANDER_STRATEGY,
            [
                $resourceShareResourceDataExpanderStrategyPlugin,
            ]
        );
    }

    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createResourceShareResourceDataExpanderStrategyPluginWhichExpandsResourceDataMock()
    {
        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginMock();
        $resourceShareResourceDataExpanderStrategyPluginMock->method('expand')
            ->willReturnCallback(function (ResourceShareTransfer $resourceShareTransfer): ResourceShareTransfer {
                return $resourceShareTransfer->setResourceData(
                    $resourceShareTransfer->getResourceData() . static::VALUE_RESOURCE_DATA_EXPANDED
                );
            });

        return $resourceShareResourceDataExpanderStrategyPluginMock;
    }

    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createResourceShareResourceDataExpanderStrategyPluginWhichReplacesResourceDataMock()
    {
        $resourceShareResourceDataExpanderStrategyPluginMock = $this->createResourceShareResourceDataExpanderStrategyPluginMock();
        $resourceShareResourceDataExpanderStrategyPluginMock->method('expand')
            ->willReturnCallback(function (ResourceShareTransfer $resourceShareTransfer): ResourceShareTransfer {
                return $resourceShareTransfer->setResourceData(
                    static::VALUE_RESOURCE_DATA_REPLACED
                );
            });

        return $resourceShareResourceDataExpanderStrategyPluginMock;
    }

    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createResourceShareResourceDataExpanderStrategyPluginMock()
    {
        return $this->createMock(ResourceShareResourceDataExpanderStrategyPluginInterface::class);
    }

    /**
     * @return \Spryker\Client\ResourceShare\ResourceShareClientInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getClient()
    {
        return $this->tester->getLocator()->resourceShare()->client();
    }
}
