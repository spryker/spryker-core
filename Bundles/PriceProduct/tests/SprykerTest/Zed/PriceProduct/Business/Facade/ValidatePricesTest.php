<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\PriceProduct\PriceProductConstants;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group ValidatePricesTest
 * Add your own group annotations below this line
 */
class ValidatePricesTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_CURRENCY = 'FAKE_CURRENCY';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @uses \Spryker\Zed\PriceProduct\Business\Validator\Constraint\ValidUniqueStoreCurrencyCollectionConstraint::MESSAGE
     *
     * @var string
     */
    protected const VALIDATION_MESSAGE_STORE_AND_CURRENCY_NEEDS_TO_BE_UNIQUE = 'The set of inputs Store and Currency needs to be unique.';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testValidatePricesIsSuccessful(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductTransfer->getMoneyValue()->setNetAmount(10);
        $priceProductTransfer->getMoneyValue()->setGrossAmount(100);

        // Act
        $validationResponseTransfer = $this->tester->getFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $this->assertTrue($validationResponseTransfer->getIsSuccess());
        $this->assertCount(0, $validationResponseTransfer->getValidationErrors());
    }

    /**
     * @return void
     */
    public function testValidatePricesFailsValidUniqueStoreCurrencyGrossNetConstraint(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        $priceProductTransfer1 = $this->tester->havePriceProductAbstract($productTransfer->getFkProductAbstract(), [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku',
        ]);
        $priceProductTransfer2 = clone $priceProductTransfer1;

        $priceProductTransfer2->getMoneyValue()->setStore($priceProductTransfer1->getMoneyValue()->getStore());
        $priceProductTransfer2->getMoneyValue()->setFkStore($priceProductTransfer1->getMoneyValue()->getFkStore());
        $priceProductTransfer2->getMoneyValue()->setCurrency($priceProductTransfer1->getMoneyValue()->getCurrency());
        $priceProductTransfer2->getMoneyValue()->setIdEntity(null);
        $priceProductTransfer2->setPriceType($priceProductTransfer1->getPriceType());
        $priceProductTransfer2->setIdProductAbstract($priceProductTransfer1->getIdProductAbstract());

        // Act
        $validationResponseTransfer = $this->tester->getFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer2]));

        // Assert
        $this->assertFalse($validationResponseTransfer->getIsSuccess());
        $this->assertCount(1, $validationResponseTransfer->getValidationErrors());
        $this->assertSame(
            static::VALIDATION_MESSAGE_STORE_AND_CURRENCY_NEEDS_TO_BE_UNIQUE,
            $validationResponseTransfer->getValidationErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testValidatePricesFailsValidUniqueStoreCurrencyCollectionConstraint(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $priceProductTransfer1 = $this->tester->havePriceProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            [
                PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku',
            ],
        );
        $priceProductTransfer1->setPriceDimension(
            (new PriceProductDimensionTransfer())
                ->setType(PriceProductConstants::PRICE_DIMENSION_DEFAULT),
        );

        $priceProductTransfer2 = clone $priceProductTransfer1;

        // Act
        $validationResponseTransfer = $this->tester->getFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer1, $priceProductTransfer2]));

        // Assert
        $this->assertFalse($validationResponseTransfer->getIsSuccess());
        $this->assertCount(1, $validationResponseTransfer->getValidationErrors());
        $this->assertSame(
            static::VALIDATION_MESSAGE_STORE_AND_CURRENCY_NEEDS_TO_BE_UNIQUE,
            $validationResponseTransfer->getValidationErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testValidatePricesFailsValidCurrencyAssignedToStoreConstraint(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]);
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceType($this->tester->havePriceType())
            ->setPriceDimension(new PriceProductDimensionTransfer())
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setStore($storeTransfer)
                    ->setCurrency((new CurrencyTransfer())->setCode(static::FAKE_CURRENCY)->setName(static::FAKE_CURRENCY))
                    ->setFkStore($storeTransfer->getIdStore())
                    ->setFkCurrency($currencyTransfer->getIdCurrency())
                    ->setGrossAmount(1)
                    ->setNetAmount(1),
            );

        // Act
        $validationResponseTransfer = $this->tester->getFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $this->assertFalse($validationResponseTransfer->getIsSuccess());
        $this->assertCount(1, $validationResponseTransfer->getValidationErrors());
        $this->assertSame(
            sprintf(
                'Currency "%s" is not assigned to the store "%s"',
                static::FAKE_CURRENCY,
                $priceProductTransfer->getMoneyValue()->getStore()->getName(),
            ),
            $validationResponseTransfer->getValidationErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testValidateFailsValidNetAmountValue(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->havePriceProductAbstract($productTransfer->getFkProductAbstract(), [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku',
        ]);
        $priceProductTransfer->getMoneyValue()->setNetAmount(-1);

        // Act
        $validationResponseTransfer = $this->tester->getFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $this->assertFalse($validationResponseTransfer->getIsSuccess());
        $this->assertSame(
            'This value is not valid.',
            $validationResponseTransfer->getValidationErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testValidatePricesFailsValidCurrencyValue(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->havePriceProductAbstract($productTransfer->getFkProductAbstract(), [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku',
        ]);
        $priceProductTransfer->getMoneyValue()->setFkCurrency(null);

        // Act
        $validationResponseTransfer = $this->tester->getFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $validationError = $validationResponseTransfer->getValidationErrors()->offsetGet(0);
        $this->assertFalse($validationResponseTransfer->getIsSuccess());
        $this->assertSame('This field is missing.', $validationError->getMessage());
        $this->assertSame('[0][moneyValue][fkCurrency]', $validationError->getPropertyPath());
    }

    /**
     * @return void
     */
    public function testValidatePricesFailsValidStoreValue(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->havePriceProductAbstract($productTransfer->getFkProductAbstract(), [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku',
        ]);
        $priceProductTransfer->getMoneyValue()->setFkStore(null);

        // Act
        $collectionValidationResponseTransfer = $this->tester->getFacade()
            ->validatePrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $validationError = $collectionValidationResponseTransfer->getValidationErrors()->offsetGet(0);
        $this->assertFalse($collectionValidationResponseTransfer->getIsSuccess());
        $this->assertSame('This field is missing.', $validationError->getMessage());
        $this->assertSame('[0][moneyValue][fkStore]', $validationError->getPropertyPath());
    }
}
