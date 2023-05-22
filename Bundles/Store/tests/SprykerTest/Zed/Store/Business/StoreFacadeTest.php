<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreConditionsTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface;
use Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException;
use Spryker\Zed\Store\Business\StoreFacade;
use Spryker\Zed\Store\StoreDependencyProvider;

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
    protected const US_STORE_NAME = 'US';

    /**
     * @uses \Spryker\Zed\Store\Business\Writer\StoreWriter::ERROR_MESSAGE_NAME_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const ERROR_MESSAGE_NAME_IS_NOT_UNIQUE = 'A store with the same name already exists.';

    /**
     * @var string
     */
    public const ALTERNATIVE_STORE_NAME = 'AT';

    /**
     * @var string
     */
    public const DEFAULT_STORE_REFERENCE = 'dev-DE';

    /**
     * @var string
     */
    protected const NON_EXISTENT_STORE_REFERENCE = 'non-Existent';

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
        $this->tester->setDependency(
            StoreDependencyProvider::STORE_CURRENT,
            static::DEFAULT_STORE_NAME,
        );
    }

    /**
     * @return void
     */
    public function testGetCurrentStoreShouldReturnCurrentStoreTransfer(): void
    {
        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME]);
        $this->tester->setDependency(StoreDependencyProvider::STORE_CURRENT, static::DEFAULT_STORE_NAME);

        // Act
        $storeTransfer = $this->createStoreFacade()->getCurrentStore();

        // Assert
        $this->assertInstanceOf(StoreTransfer::class, $storeTransfer);
        $this->assertNotEmpty($storeTransfer->getName());
        $this->assertNotEmpty($storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testGetAllStoresShouldReturnAllStore(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();

        //Act
        $stores = $storeFacade->getAllStores();

        //Assert
        $this->assertIsArray($stores);
    }

    /**
     * @return void
     */
    public function testGetStoreByIdShouldReturnStoreFromPersistence(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();

        //Act
        $storeTransfer = $storeFacade->getStoreById(1);

        //Assert
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
        //Arrange
        $storeFacade = $this->createStoreFacade();

        //Act
        $storeTransfer = $storeFacade->getStoreByName(static::DEFAULT_STORE_NAME);

        //Assert
        $this->assertInstanceOf(StoreTransfer::class, $storeTransfer);
        $this->assertSame(static::DEFAULT_STORE_NAME, $storeTransfer->getName());
        $this->assertSame(static::DEFAULT_STORE_REFERENCE, $storeTransfer->getStoreReference());
        $this->assertNotEmpty($storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testFindStoreByNameShouldReturnStore(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();

        //Act
        $storeTransfer = $storeFacade->findStoreByName(static::DEFAULT_STORE_NAME);

        //Assert
        $this->assertInstanceOf(StoreTransfer::class, $storeTransfer);
        $this->assertSame(static::DEFAULT_STORE_NAME, $storeTransfer->getName());
        $this->assertNotEmpty($storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testFindStoreByWrongNameShouldReturnNull(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();

        //Act
        $storeTransfer = $storeFacade->findStoreByName('NullName');

        //Assert
        $this->assertNull($storeTransfer);
    }

    /**
     * @return void
     */
    public function testGetStoreTransfersByStoreNamesReturnStores(): void
    {
        //Arrange
        $this->tester->setDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER, []);
        $storeFacade = $this->createStoreFacade();
        $storeNames = [
            static::DEFAULT_STORE_NAME,
            static::ALTERNATIVE_STORE_NAME,
        ];

        //Act
        $storeTransfers = $storeFacade->getStoreTransfersByStoreNames($storeNames);

        //Assert
        $this->assertEquals(static::DEFAULT_STORE_NAME, $storeTransfers[0]->getName());
        $this->assertEquals(static::ALTERNATIVE_STORE_NAME, $storeTransfers[1]->getName());
    }

    /**
     * @return void
     */
    public function testCreateStoreShouldReturnCreatedStore(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $storeTransfer = (new StoreTransfer())->setName('NEW_NAME');
        $storeTransfer->setAvailableCurrencyIsoCodes([
            'EUR',
            'USD',
        ]);
        $storeTransfer->setAvailableLocaleIsoCodes([
            'de_DE',
            'en_US',
        ]);

        //Act
        $storeResponseTransfer = $storeFacade->createStore($storeTransfer);

        //Assert
        $this->assertTrue($storeResponseTransfer->getIsSuccessful());
        $this->assertNotNull($storeResponseTransfer->getStoreOrFail()->getAvailableCurrencyIsoCodes());
        $this->assertNotNull($storeResponseTransfer->getStoreOrFail()->getAvailableLocaleIsoCodes());
    }

    /**
     * @return void
     */
    public function testUpdateStoreCurrencyShouldReturnUpdatedData(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $storeTransfer = $storeFacade->getStoreByName(static::DEFAULT_STORE_NAME);

        $currencies = [
            'EUR',
            'USD',
        ];
        $storeTransfer->setAvailableCurrencyIsoCodes($currencies);

        //Act
        $storeResponseTransfer = $storeFacade->updateStore($storeTransfer);

        //Assert
        $this->assertTrue($storeResponseTransfer->getIsSuccessful());
        $this->assertSame($currencies, $storeResponseTransfer->getStoreOrFail()->getAvailableCurrencyIsoCodes());
    }

    /**
     * @return void
     */
    public function testUpdateStoreLocaleShouldReturnUpdatedData(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $storeTransfer = $storeFacade->getStoreByName(static::DEFAULT_STORE_NAME);

        $locales = [
            'en_GB',
            'de_AT',
        ];
        $storeTransfer->setAvailableLocaleIsoCodes($locales);

        //Act
        $storeResponseTransfer = $storeFacade->updateStore($storeTransfer);

        //Assert
        $this->assertTrue($storeResponseTransfer->getIsSuccessful());
        $this->assertSame($locales, $storeResponseTransfer->getStoreOrFail()->getAvailableLocaleIsoCodes());
    }

    /**
     * @return void
     */
    public function testUpdateStoreShouldReturnUpdatedStore(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $storeTransfer = $storeFacade->getStoreByName(static::DEFAULT_STORE_NAME);

        //Act
        $storeResponseTransfer = $storeFacade->updateStore($storeTransfer);

        //Assert
        $resultStore = $storeResponseTransfer->getStore();
        $this->assertTrue($storeResponseTransfer->getIsSuccessful());
        $this->assertSame($storeTransfer->getIdStore(), $resultStore->getIdStore());
    }

    /**
     * @return void
     */
    public function testGetStoreCollectionByCriteriaShouldReturnStore(): void
    {
        //Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME]);
        $this->tester->setDependency(StoreDependencyProvider::STORE, $this->getStoreToStoreInterface());
        $storeFacade = $this->createStoreFacade();
        $storeCriteriaTransfer = (new StoreCriteriaTransfer())
            ->setStoreConditions((new StoreConditionsTransfer())
                ->setStoreIds([$storeTransfer->getIdStoreOrFail()]));

        //Act
        $storeCollectionTransfer = $storeFacade->getStoreCollection($storeCriteriaTransfer);

        //Assert
        $this->assertInstanceOf(StoreCollectionTransfer::class, $storeCollectionTransfer);
        $foundStoreTransfers = $storeCollectionTransfer->getStores()->getArrayCopy();

        $this->assertCount(1, $foundStoreTransfers);
        $this->assertSame($storeTransfer->getNameOrFail(), $foundStoreTransfers[0]->getName());
        $this->assertSame($storeTransfer->getIdStoreOrFail(), $foundStoreTransfers[0]->getIdStore());
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
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return void
     */
    public function testGetStoresAvailableForCurrentPersistenceWillReturnCurrentStoreWithSharedStores(): void
    {
        if ($this->isDynamicStoreEnabled()) {
            $this->markTestSkipped('Test is not applicable for dynamic store due to different store configuration.');
        }

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
    public function testCreateStoreWithNotUniqueName(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())->setName(static::DEFAULT_STORE_NAME);

        // Act
        $storeResponseTransfer = $this->createStoreFacade()->createStore($storeTransfer);

        // Assert
        $this->assertFalse($storeResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::ERROR_MESSAGE_NAME_IS_NOT_UNIQUE,
            $storeResponseTransfer->getMessages()[0]->getValue(),
        );
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
    public function testStoreNameIsReadOnlyAndCannotBeUpdated(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME]);
        $storeTransfer->setName(static::US_STORE_NAME);

        // Act
        $storeResponseTransfer = $this->createStoreFacade()->updateStore($storeTransfer);

        // Assert
        $this->assertTrue($storeResponseTransfer->getIsSuccessful());
        $this->assertSame($storeTransfer->getName(), static::DEFAULT_STORE_NAME);
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
        $this->tester->setDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER, []);

        $messageTransfer = $this->tester->createMessageBrokerTestMessageTransfer(static::NON_EXISTENT_STORE_REFERENCE);

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
        $messageAttributesTransfer = $this->tester->createMessageAttributesTransfer([
            MessageAttributesTransfer::STORE_REFERENCE => static::DEFAULT_STORE_REFERENCE,
        ]);

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
     * @return void
     */
    public function testAccessTokenRequestNotExpandedWithStoreReferenceWhenItsMissing(): void
    {
        // Arrange
        $this->tester->setStoreReferenceData([]);

        $accessTokenRequestTransfer = $this->tester->createAccessTokenRequestTransfer(
            [
                'storeReference' => null,
            ],
        );

        // Act
        $accessTokenRequestTransfer = $this->tester->getFacade()->expandAccessTokenRequest($accessTokenRequestTransfer);

        // Assert
        $this->assertNull($accessTokenRequestTransfer->getAccessTokenRequestOptions()->getStoreReference());
    }

    /**
     * @return void
     */
    public function testAccessTokenRequestSuccessfullyExpandedWithStoreReference(): void
    {
        // Arrange
        $storeReference = 'dev-DE';
        $accessTokenRequestTransfer = $this->tester->createAccessTokenRequestTransfer([
            'storeReference' => $storeReference,
        ]);

        // Act
        $accessTokenRequestTransfer = $this->tester->getFacade()->expandAccessTokenRequest($accessTokenRequestTransfer);

        // Assert
        $this->assertNotNull($accessTokenRequestTransfer->getAccessTokenRequestOptions()->getStoreReference());
        $this->assertEquals($storeReference, $accessTokenRequestTransfer->getAccessTokenRequestOptions()->getStoreReference());
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacade
     */
    protected function createStoreFacade(): StoreFacade
    {
        return new StoreFacade();
    }

    /**
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    protected function getStoreToStoreInterface(): StoreToStoreInterface
    {
        return $this->getMockBuilder(StoreToStoreInterface::class)->getMock();
    }

    /**
     * @return bool
     */
    protected function isDynamicStoreEnabled(): bool
    {
        return (bool)getenv('SPRYKER_DYNAMIC_STORE_MODE');
    }
}
