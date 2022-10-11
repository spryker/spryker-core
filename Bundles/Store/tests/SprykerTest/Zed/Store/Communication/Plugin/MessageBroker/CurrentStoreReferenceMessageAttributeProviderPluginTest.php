<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\Model\StoreReader;
use Spryker\Zed\Store\Communication\Plugin\MessageBroker\CurrentStoreReferenceMessageAttributeProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Store
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group CurrentStoreReferenceMessageAttributeProviderPluginTest
 * Add your own group annotations below this line
 */
class CurrentStoreReferenceMessageAttributeProviderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME = 'boo';

    /**
     * @var string
     */
    protected const STORE_REFERENCE_NAME = 'development_test-boo';

    /**
     * @var \SprykerTest\Zed\Store\StoreCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProvideMessageAttributesAddsStoreReferenceWhenItExists(): void
    {
        // Arrange
        $this->mockCurrentStore(static::STORE_NAME, static::STORE_REFERENCE_NAME);

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $storeReferenceMessageAttributeProviderPlugin = new CurrentStoreReferenceMessageAttributeProviderPlugin();
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
        $this->mockCurrentStore('aaa', null);

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $storeReferenceMessageAttributeProviderPlugin = new CurrentStoreReferenceMessageAttributeProviderPlugin();
        $storeReferenceMessageAttributeProviderPlugin->setFacade($this->tester->getFacade());

        // Act
        $messageAttributesTransfer = $storeReferenceMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertNull($messageAttributesTransfer->getStoreReference());
    }

    /**
     * @param string $storeName
     * @param string|null $storeReference
     *
     * @return void
     */
    protected function mockCurrentStore(string $storeName, ?string $storeReference): void
    {
        $storeReaderMock = $this->getMockBuilder(StoreReader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeReaderMock->method('getCurrentStore')->willReturn(
            (new StoreTransfer())
                ->setName($storeName)
                ->setStoreReference($storeReference),
        );

        $this->tester->mockFactoryMethod('createStoreReader', $storeReaderMock);
    }
}
