<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\MultiCart\Storage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToStoreClientInterface;
use Spryker\Client\MultiCart\MultiCartConfig;
use Spryker\Client\MultiCart\Storage\MultiCartStorage;
use SprykerTest\Client\MultiCart\MultiCartClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group MultiCart
 * @group Storage
 * @group MultiCartStorageTest
 * Add your own group annotations below this line
 */
class MultiCartStorageTest extends Unit
{
    /**
     * @var \SprykerTest\Client\MultiCart\MultiCartClientTester
     */
    protected MultiCartClientTester $tester;

    /**
     * @return void
     */
    public function testSetQuoteCollectionSetsQuoteKeyWithCurrentStorePrefixIfDynamicStoreIsEnabled(): void
    {
        //Arrange
        $quoteCollectionTransfer = (new QuoteCollectionTransfer())
            ->addQuote($this->tester->haveQuote())
            ->addQuote($this->tester->haveQuote());
        $storeTransfer = $this->tester->getLocator()->store()->client()->getCurrentStore();
        $expectedSessionKey = sprintf('%s_%s', $storeTransfer->getName(), $this->tester::SESSION_KEY_QUOTE_COLLECTION);

        //Assert
        $storeClientMock = $this->createStoreClientMock();
        $storeClientMock->expects($this->once())
            ->method('getCurrentStore')
            ->willReturn($storeTransfer);

        $sessionClientMock = $this->createSessionClientMock();
        $sessionClientMock->expects($this->once())
            ->method('set')
            ->willReturnCallback(function (string $quoteCollectionKey) use ($expectedSessionKey) {
                $this->assertSame($expectedSessionKey, $quoteCollectionKey);
            });

        //Act
        $multiCartStorage = new MultiCartStorage(
            $sessionClientMock,
            $storeClientMock,
            $this->createMultiCartConfig(),
        );
        $multiCartStorage->setQuoteCollection($quoteCollectionTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface
     */
    protected function createSessionClientMock(): MultiCartToSessionClientInterface
    {
        return $this->createMock(MultiCartToSessionClientInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\MultiCart\Dependency\Client\MultiCartToStoreClientInterface
     */
    protected function createStoreClientMock(): MultiCartToStoreClientInterface
    {
        $storeClientMock = $this->createMock(MultiCartToStoreClientInterface::class);

        return $storeClientMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\MultiCart\MultiCartConfig
     */
    protected function createMultiCartConfig(): MultiCartConfig
    {
        $configMock = $this->createMock(MultiCartConfig::class);
        $configMock->expects($this->once())
            ->method('getQuoteFieldsAllowedForCustomerQuoteCollectionInSession')
            ->willReturn([
                QuoteTransfer::ID_QUOTE,
                QuoteTransfer::STORE => [
                    StoreTransfer::ID_STORE,
                    StoreTransfer::NAME,
                ],
            ]);

        return $configMock;
    }
}
