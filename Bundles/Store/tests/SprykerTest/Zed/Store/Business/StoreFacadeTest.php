<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException;
use Spryker\Zed\Store\Business\StoreFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Store
 * @group Business
 * @group Facade
 * @group StoreFacadeTest
 * Add your own group annotations below this line
 */
class StoreFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const DEFAULT_STORE_NAME = 'DE';

    /**
     * @var string
     */
    public const ALTERNATIVE_STORE_NAME = 'AT';

    /**
     * @var string
     */
    public const DEFAULT_STORE_REFERENCE = 'dev-DE';

    /**
     * @var \SprykerTest\Zed\Store\StoreBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setStoreReferenceData([
            'DE' => 'dev-DE',
            'AT' => 'dev-AT',
        ]);
    }

    /**
     * @return void
     */
    public function testGetCurrentStoreShouldReturnCurrentStoreTransfer(): void
    {
        $storeFacade = $this->createStoreFacade();

        $storeTransfer = $storeFacade->getCurrentStore();

        $this->assertInstanceOf(StoreTransfer::class, $storeTransfer);
        $this->assertNotEmpty($storeTransfer->getName());
        $this->assertNotEmpty($storeTransfer->getIdStore());
        $this->assertNotEmpty($storeTransfer->getStoreReference());
    }

    /**
     * @return void
     */
    public function testGetAllStoresShouldReturnAllStore(): void
    {
        $storeFacade = $this->createStoreFacade();

        $stores = $storeFacade->getAllStores();

        $this->assertIsArray($stores);
    }

    /**
     * @return void
     */
    public function testGetStoreByIdShouldReturnStoreFromPersistence(): void
    {
        $storeFacade = $this->createStoreFacade();

        $storeTransfer = $storeFacade->getStoreById(1);

        $this->assertInstanceOf(StoreTransfer::class, $storeTransfer);
        $this->assertNotEmpty($storeTransfer->getName());
        $this->assertNotEmpty($storeTransfer->getIdStore());
        $this->assertNotEmpty($storeTransfer->getStoreReference());
    }

    /**
     * @return void
     */
    public function testGetStoreByNameShouldReturnStore(): void
    {
        $storeFacade = $this->createStoreFacade();
        $storeTransfer = $storeFacade->getStoreByName(static::DEFAULT_STORE_NAME);

        $this->assertInstanceOf(StoreTransfer::class, $storeTransfer);

        $this->assertSame(static::DEFAULT_STORE_NAME, $storeTransfer->getName());
        $this->assertSame(static::DEFAULT_STORE_REFERENCE, $storeTransfer->getStoreReference());
        $this->assertNotEmpty($storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testValidateQuoteStoreWithMissingStore(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $quoteTransfer = new QuoteTransfer();

        //Act
        $quoteValidationTransfer = $storeFacade->validateQuoteStore($quoteTransfer);

        //Assert
        $this->assertFalse($quoteValidationTransfer->getIsSuccessful());
        $this->assertSame(1, $quoteValidationTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testValidateQuoteStoreWithMissingStoreName(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $quoteTransfer = (new QuoteTransfer())->setStore(new StoreTransfer());

        //Act
        $quoteValidationTransfer = $storeFacade->validateQuoteStore($quoteTransfer);

        //Assert
        $this->assertFalse($quoteValidationTransfer->getIsSuccessful());
        $this->assertSame(1, $quoteValidationTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testValidateQuoteStoreWithWrongStoreName(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $quoteTransfer = (new QuoteTransfer())->setStore((new StoreTransfer())->setName('WrongStore'));

        //Act
        $quoteValidationTransfer = $storeFacade->validateQuoteStore($quoteTransfer);

        //Assert
        $this->assertFalse($quoteValidationTransfer->getIsSuccessful());
        $this->assertSame(1, $quoteValidationTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testValidateQuoteStoreWithCorrectStoreName(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $quoteTransfer = (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::DEFAULT_STORE_NAME));

        //Act
        $quoteValidationTransfer = $storeFacade->validateQuoteStore($quoteTransfer);

        //Assert
        $this->assertTrue($quoteValidationTransfer->getIsSuccessful());
        $this->assertSame(0, $quoteValidationTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testGetStoresAvailableForCurrentPersistenceWillReturnCurrentStoreWithSharedStores(): void
    {
        // Arrange
        $storeFacade = $this->createStoreFacade();

        $currentStoreTransfer = $storeFacade->getCurrentStore();
        $expectedStoreNames = array_merge([$currentStoreTransfer->getName()], $currentStoreTransfer->getStoresWithSharedPersistence());

        // Act
        $availableStoreTransfers = $storeFacade->getStoresAvailableForCurrentPersistence();

        // Assert
        $availableStoreNames = array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getName();
        }, $availableStoreTransfers);

        $this->assertSame($expectedStoreNames, $availableStoreNames, 'Available stores should contain stores with shared persistence.');
    }

    /**
     * @return void
     */
    public function testGetStoreByStoreReferenceReturnsExpectedTransferWhenInputArgumentIsCorrect(): void
    {
        // Arrange
        $this->tester->setStoreReferenceData([static::DEFAULT_STORE_NAME => static::DEFAULT_STORE_REFERENCE]);

        // Act
        $storeTransfer = $this->tester->getFacade()->getStoreByStoreReference(static::DEFAULT_STORE_REFERENCE);

        // Assert
        $this->assertSame(static::DEFAULT_STORE_REFERENCE, $storeTransfer->getStoreReference());
        $this->assertSame(static::DEFAULT_STORE_NAME, $storeTransfer->getName());
    }

    /**
     * @return void
     */
    public function testGetStoreByStoreReferenceThrowsExceptionWhenInputArgumentIsNotCorrect(): void
    {
        // Arrange
        $invalidStoreReference = '1';

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $this->tester->getFacade()->getStoreByStoreReference($invalidStoreReference);
    }

    /**
     * @return void
     */
    public function testMessageTransferValidationIsSuccessful(): void
    {
        // Arrange
        $storeFacade = $this->createStoreFacade();
        $storeTransfer = $storeFacade->getStoreByName(static::DEFAULT_STORE_NAME);
        $messageTransfer = $this->tester->createMessageBrokerTestMessageTransfer($storeTransfer->getStoreReference());

        // Act
        $messageValidationResponseTransfer = $this->tester->getFacade()->validateMessageTransfer($messageTransfer);

        // Assert
        $this->assertTrue($messageValidationResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testMessageTransferValidationFailsWhenCurrentStoreIsMissing(): void
    {
        // Arrange
        $messageTransfer = $this->tester->createMessageBrokerTestMessageTransfer();

        // Act
        $messageValidationResponseTransfer = $this->tester->getFacade()->validateMessageTransfer($messageTransfer);

        // Assert
        $this->assertFalse($messageValidationResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testMessageTransferValidationFailsWhenStoreReferenceMismatch(): void
    {
        // Arrange
        $storeFacade = $this->createStoreFacade();
        $storeTransfer = $storeFacade->getStoreByName(static::ALTERNATIVE_STORE_NAME);
        $messageTransfer = $this->tester->createMessageBrokerTestMessageTransfer($storeTransfer->getStoreReference());

        // Act
        $messageValidationResponseTransfer = $this->tester->getFacade()->validateMessageTransfer($messageTransfer);

        // Assert
        $this->assertFalse($messageValidationResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testMessageAttributesSuccessfullyExpandedWithStoreReference(): void
    {
        // Arrange
        $messageAttributesTransfer = $this->tester->createMessageAttributesTransfer();

        // Act
        $messageAttributesTransfer = $this->tester->getFacade()->expandMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertEquals(static::DEFAULT_STORE_REFERENCE, $messageAttributesTransfer->getStoreReference());
        $this->assertNotEquals(static::DEFAULT_STORE_REFERENCE, $messageAttributesTransfer->getEmitter());
    }

    /**
     * @return void
     */
    public function testMessageAttributesNotExpandedWithStoreReferenceWhenItsMissing(): void
    {
        // Arrange
        $this->tester->setStoreReferenceData([]);

        $messageAttributesTransfer = $this->tester->createMessageAttributesTransfer(
            [
                'storeReference' => null,
                'emitter' => null,
            ],
        );

        // Act
        $messageAttributesTransfer = $this->tester->getFacade()->expandMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertNull($messageAttributesTransfer->getStoreReference());
        $this->assertNull($messageAttributesTransfer->getEmitter());
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacade
     */
    protected function createStoreFacade(): StoreFacade
    {
        return new StoreFacade();
    }
}
