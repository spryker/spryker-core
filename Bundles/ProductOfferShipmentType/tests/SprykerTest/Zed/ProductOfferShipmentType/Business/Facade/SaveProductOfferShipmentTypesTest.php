<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentType\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\ProductOfferShipmentType\Business\Exception\ProductOfferValidationException;
use SprykerTest\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShipmentType
 * @group Business
 * @group Facade
 * @group SaveProductOfferShipmentTypesTest
 * Add your own group annotations below this line
 */
class SaveProductOfferShipmentTypesTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer\ShipmentTypeUniquenessProductOfferValidatorRule::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NOT_UNIQUE = 'product_offer_shipment_type.validation.shipment_type_not_unique';

    /**
     * @uses \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer\ProductOfferUniquenessValidatorRule::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_NOT_UNIQUE = 'product_offer_shipment_type.validation.product_offer_not_unique';

    /**
     * @uses \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer\ProductOfferExistsProductOfferValidatorRule::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_REFERENCE_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_REFERENCE_NOT_FOUND = 'product_offer_shipment_type.validation.product_offer_reference_not_found';

    /**
     * @uses \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer\ShipmentTypeExistsProductOfferValidatorRule::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_UUID_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_UUID_NOT_FOUND = 'product_offer_shipment_type.validation.shipment_type_uuid_not_found';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_INVALID = 'not-existing-product-offer-reference';

    /**
     * @var string
     */
    protected const UUID_SHIPMENT_TYPE_INVALID = 'not-existing-shipment-type-uuid';

    /**
     * @var \SprykerTest\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeBusinessTester
     */
    protected ProductOfferShipmentTypeBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty($this->tester->getProductOfferShipmentTypeQuery());
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testCreatesOneProductOfferShipmentType(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addShipmentType($this->tester->haveShipmentType());
        $productOfferShipmentTypeCollectionRequestTransfer->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasShipmentTypesPersisted($productOfferTransfer);
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testCreatesFewProductOfferShipmentTypesForOneProductOffer(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addShipmentType($this->tester->haveShipmentType());
        $productOfferTransfer->addShipmentType($this->tester->haveShipmentType());
        $productOfferShipmentTypeCollectionRequestTransfer->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasShipmentTypesPersisted($productOfferTransfer);
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testCreatesFewProductOfferShipmentTypesForDifferentProductOffers(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer1 = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $productOfferTransfer1->addShipmentType($this->tester->haveShipmentType());
        $productOfferTransfer2->addShipmentType($this->tester->haveShipmentType());
        $productOfferShipmentTypeCollectionRequestTransfer
            ->addProductOffer($productOfferTransfer1)
            ->addProductOffer($productOfferTransfer2);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasShipmentTypesPersisted($productOfferTransfer1);
        $this->assertProductOfferHasShipmentTypesPersisted($productOfferTransfer2);
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testDeletesOldAndCreateNewProductOfferShipmentTypes(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $this->tester->haveShipmentType());
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $this->tester->haveShipmentType());

        $persistedShipmentTypeTransfer = $this->tester->haveShipmentType();

        $productOfferTransfer->addShipmentType($persistedShipmentTypeTransfer);
        $productOfferShipmentTypeCollectionRequestTransfer->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasShipmentTypesPersisted($productOfferTransfer);
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testDeletesAllProductOfferShipmentTypes(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $this->tester->haveShipmentType());
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $this->tester->haveShipmentType());

        $productOfferShipmentTypeCollectionRequestTransfer->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasShipmentTypesPersisted($productOfferTransfer);
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testDoesNothingWhenNoShipmentTypesExistAndNewOnesAreNotProvided(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $this->tester->haveShipmentType());
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $this->tester->haveShipmentType());

        $productOfferShipmentTypeCollectionRequestTransfer->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasShipmentTypesPersisted($productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenShipmentTypeIsDuplicatedInProductOffer(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $persistedShipmentTypeTransfer = $this->tester->haveShipmentType();
        $productOfferTransfer
            ->addShipmentType($persistedShipmentTypeTransfer)
            ->addShipmentType($persistedShipmentTypeTransfer);
        $productOfferShipmentTypeCollectionRequestTransfer = (new ProductOfferShipmentTypeCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(true)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsValidationErrorWhenShipmentTypeIsDuplicatedInProductOffer(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $persistedShipmentTypeTransfer = $this->tester->haveShipmentType();
        $productOfferTransfer
            ->addShipmentType($persistedShipmentTypeTransfer)
            ->addShipmentType($persistedShipmentTypeTransfer);
        $productOfferShipmentTypeCollectionRequestTransfer = (new ProductOfferShipmentTypeCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(false)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferShipmentTypeCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferShipmentTypeCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferShipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getNumberOfPersistedProductOfferShipmentTypes($productOfferTransfer));
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductOfferIsDuplicated(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addShipmentType($this->tester->haveShipmentType());
        $productOfferShipmentTypeCollectionRequestTransfer = (new ProductOfferShipmentTypeCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(true)
            ->addProductOffer($productOfferTransfer)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsValidationErrorWhenProductOfferIsDuplicated(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addShipmentType($this->tester->haveShipmentType());
        $productOfferShipmentTypeCollectionRequestTransfer = (new ProductOfferShipmentTypeCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(false)
            ->addProductOffer($productOfferTransfer)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferShipmentTypeCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferShipmentTypeCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferShipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_NOT_UNIQUE, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getNumberOfPersistedProductOfferShipmentTypes($productOfferTransfer));
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductOfferReferenceIsInvalid(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addShipmentType($this->tester->haveShipmentType());
        $productOfferTransfer->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_INVALID);
        $productOfferShipmentTypeCollectionRequestTransfer = (new ProductOfferShipmentTypeCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(true)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsValidationErrorWhenProductOfferReferenceIsInvalid(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addShipmentType($this->tester->haveShipmentType());
        $productOfferTransfer->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_INVALID);
        $productOfferShipmentTypeCollectionRequestTransfer = (new ProductOfferShipmentTypeCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(false)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferShipmentTypeCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferShipmentTypeCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferShipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_REFERENCE_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getNumberOfPersistedProductOfferShipmentTypes($productOfferTransfer));
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenShipmentTypeUuidIsInvalid(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $shipmentTypeTransfer->setUuid(static::UUID_SHIPMENT_TYPE_INVALID);
        $productOfferTransfer->addShipmentType($shipmentTypeTransfer);
        $productOfferShipmentTypeCollectionRequestTransfer = (new ProductOfferShipmentTypeCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(true)
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(ProductOfferValidationException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsValidationErrorWhenShipmentTypeUuidIsInvalid(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $shipmentTypeTransfer->setUuid(static::UUID_SHIPMENT_TYPE_INVALID);
        $productOfferTransfer->addShipmentType($shipmentTypeTransfer);
        $productOfferShipmentTypeCollectionRequestTransfer = (new ProductOfferShipmentTypeCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(false)
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferShipmentTypeCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productOfferShipmentTypeCollectionResponseTransfer->getErrors());

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        $errorTransfer = $productOfferShipmentTypeCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_UUID_NOT_FOUND, $errorTransfer->getMessageOrFail());
        $this->assertSame(0, $this->tester->getNumberOfPersistedProductOfferShipmentTypes($productOfferTransfer));
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testShouldExpandProductOfferWithIdProductOfferWhenIdProductOfferIsMissing(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addShipmentType($this->tester->haveShipmentType());
        $expectedIdProductOffer = $productOfferTransfer->getIdProductOfferOrFail();
        $productOfferTransfer->setIdProductOffer(null);

        $productOfferShipmentTypeCollectionRequestTransfer->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasShipmentTypesPersisted($productOfferTransfer);
        $this->assertSame($expectedIdProductOffer, $productOfferTransfer->getIdProductOfferOrFail());
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testShouldExpandProductOfferWithShipmentTypeIdWhenShipmentTypeIdIsMissing(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $expectedIdShipmentType = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
        $shipmentTypeTransfer->setIdShipmentType(null);
        $productOfferTransfer->addShipmentType($shipmentTypeTransfer);

        $productOfferShipmentTypeCollectionRequestTransfer->addProductOffer($productOfferTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);

        // Assert
        $this->assertProductOfferHasShipmentTypesPersisted($productOfferTransfer);
        $this->assertSame(
            $expectedIdShipmentType,
            $productOfferTransfer->getShipmentTypes()->getIterator()->current()->getIdShipmentTypeOrFail(),
        );
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testShouldThrowExceptionWhenProductOfferReferenceIsMissing(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer->addShipmentType($this->tester->haveShipmentType());
        $productOfferTransfer->setProductOfferReference(null);
        $productOfferShipmentTypeCollectionRequestTransfer
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);
    }

    /**
     * @dataProvider getProductOfferShipmentTypeCollectionRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    public function testShouldThrowExceptionWhenShipmentTypeUuidIsMissing(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $shipmentTypeTransfer->setUuid(null);
        $productOfferTransfer->addShipmentType($shipmentTypeTransfer);
        $productOfferShipmentTypeCollectionRequestTransfer->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->saveProductOfferShipmentTypes($productOfferShipmentTypeCollectionRequestTransfer);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getProductOfferShipmentTypeCollectionRequestDataProvider(): array
    {
        return [
            'Transactional, Throw exception on validation' => [(new ProductOfferShipmentTypeCollectionRequestTransfer())->setIsTransactional(true)->setThrowExceptionOnValidation(true)],
            'Transactional, Do not throw exception on validation' => [(new ProductOfferShipmentTypeCollectionRequestTransfer())->setIsTransactional(true)->setThrowExceptionOnValidation(false)],
            'Not transactional, Throw exception on validation' => [(new ProductOfferShipmentTypeCollectionRequestTransfer())->setIsTransactional(false)->setThrowExceptionOnValidation(true)],
            'Not transactional, Do not throw exception on validation' => [(new ProductOfferShipmentTypeCollectionRequestTransfer())->setIsTransactional(false)->setThrowExceptionOnValidation(false)],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    protected function assertProductOfferHasShipmentTypesPersisted(ProductOfferTransfer $productOfferTransfer): void
    {
        $this->assertSame($productOfferTransfer->getShipmentTypes()->count(), $this->tester->getNumberOfPersistedProductOfferShipmentTypes($productOfferTransfer));

        foreach ($productOfferTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $this->assertTrue($this->tester->hasProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer));
        }
    }
}
