<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreReference\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreReference\Communication\Plugin\MessageBroker\StoreReferenceMessageAttributeProviderPlugin;
use Spryker\Zed\StoreReference\Dependency\Facade\StoreReferenceToStoreBridge;
use Spryker\Zed\StoreReference\StoreReferenceDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreReference
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group StoreReferenceMessageAttributeProviderPluginTest
 * Add your own group annotations below this line
 */
class StoreReferenceMessageAttributeProviderPluginTest extends Unit
{
    /**
     * @var array
     */
    protected const STORE_NAME_REFERENCE_MAP = [
        'boo' => 'development_test-boo',
        'foo' => 'development_test-foo',
    ];

    /**
     * @var string
     */
    protected const STORE_NAME = 'boo';

    /**
     * @var string
     */
    protected const STORE_REFERENCE_NAME = 'development_test-boo';

    /**
     * @var \SprykerTest\Zed\StoreReference\StoreReferenceCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProvideMessageAttributesAddsStoreReferenceWhenItExists(): void
    {
        // Arrange
        $this->mockStoreFacadeDefaultStore();
        $this->tester->mockStoreNameReferenceMap(static::STORE_NAME_REFERENCE_MAP);

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $storeReferenceMessageAttributeProviderPlugin = new StoreReferenceMessageAttributeProviderPlugin();
        $storeReferenceMessageAttributeProviderPlugin->setFacade($this->tester->getFacade());

        // Act
        $messageAttributesTransfer = $storeReferenceMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertSame(static::STORE_REFERENCE_NAME, $messageAttributesTransfer->getStoreReference());
    }

    /**
     * @return void
     */
    public function testProvideMessageAttributesDoesNotAddStoreReferenceWhenTenantIdentifierDoesNotExists(): void
    {
        // Arrange
        $this->tester->mockStoreNameReferenceMap(static::STORE_NAME_REFERENCE_MAP);
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $storeReferenceMessageAttributeProviderPlugin = new StoreReferenceMessageAttributeProviderPlugin();
        $storeReferenceMessageAttributeProviderPlugin->setFacade($this->tester->getFacade());

        // Act
        $messageAttributesTransfer = $storeReferenceMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertNull($messageAttributesTransfer->getStoreReference());
    }

    /**
     * @return void
     */
    protected function mockStoreFacadeDefaultStore(): void
    {
        $storeFacadeMock = $this->getMockBuilder(StoreReferenceToStoreBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeFacadeMock->method('getCurrentStore')->willReturn(
            (new StoreTransfer())->setName(static::STORE_NAME),
        );

        $this->tester->setDependency(StoreReferenceDependencyProvider::FACADE_STORE, $storeFacadeMock);
    }
}
