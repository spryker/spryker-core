<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Stub;
use Generated\Shared\DataBuilder\ProductPackagingUnitTypeBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeUniqueViolationException;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group Facade
 * @group ProductPackagingUnitFacadeTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitFacadeTest extends ProductPackagingUnitMocks
{
    protected const PACKAGING_TYPE_DEFAULT = 'item';
    protected const PACKAGING_TYPE = 'box';

    protected const ITEM_QUANTITY = 2;
    protected const PACKAGE_AMOUNT = 4;

    protected const GROUP_KEY = 'GROUP_KEY_DUMMY';
    protected const GROUP_KEY_FORMAT = '%s_amount_%s_sales_unit_id_%s';
    protected const AMOUNT_VALUE = 5;
    protected const SALES_UNIT_ID = 5;

    protected const DEFAULT_PRODUCT_PACKAGING_UNIT_TYPE_NAME = 'packaging_unit_type.item.name';

    protected const PRICE_MODE_NET = 'NET_MODE';
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    protected const ABSTRACT_PRODUCT_SKU = 'ABSTRACT_PRODUCT_SKU';
    protected const CONCRETE_PRODUCT_SKU = 'CONCRETE_PRODUCT_SKU';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testInstallProductPackagingUnitTypesShouldPersistInfrastructuralPackagingUnitTypes(): void
    {
        // Assign
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())->build();
        $config = $this->createProductPackagingUnitConfigMock();
        $config->method('getInfrastructuralPackagingUnitTypes')
            ->willReturn([$productPackagingUnitTypeTransfer]);
        $factory = $this->createProductPackagingUnitBusinessFactoryMock($config);
        $facade = $this->createProductPackagingUnitFacadeMock($factory);

        // Action
        $facade->installProductPackagingUnitTypes();

        // Assert
        $productPackagingUnitTypeTransfer = $this->getFacade()->findProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);
        $this->assertNotNull($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
    }

    /**
     * @dataProvider getProductPackagingUnitTypeData
     *
     * @param string $name
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer ...$nameTranslations
     *
     * @return void
     */
    public function testCreateProductPackagingUnitTypeShouldPersistPackagingUnitType(string $name, ProductPackagingUnitTypeTranslationTransfer ... $nameTranslations): void
    {
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        foreach ($nameTranslations as $nameTranslation) {
            $productPackagingUnitTypeTransfer->addProductPackagingUnitTypeTranslation($nameTranslation);
        }

        // Action
        $this->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);
        $productPackagingUnitTypeTransfer = $this->getFacade()->findProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);
        $this->assertNotNull($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
        // Assert translations persisted
        $this->assertCount($productPackagingUnitTypeTransfer->getTranslations()->count(), $nameTranslations);
    }

    /**
     * @dataProvider getProductPackagingUnitTypeData
     *
     * @param string $name
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer ...$nameTranslations
     *
     * @return void
     */
    public function testCreateProductPackagingUnitTypeShouldThrowExceptionIfDuplicateUnitTypeIsTryingToBeAdded(string $name, ProductPackagingUnitTypeTranslationTransfer ... $nameTranslations): void
    {
        // Arrange
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        foreach ($nameTranslations as $nameTranslation) {
            $productPackagingUnitTypeTransfer->addProductPackagingUnitTypeTranslation($nameTranslation);
        }
        $this->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Assert
        $this->expectException(ProductPackagingUnitTypeUniqueViolationException::class);

        // Act
        $this->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }

    /**
     * @dataProvider getProductPackagingUnitTypeData
     *
     * @expectedException \Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeNotFoundException
     *
     * @param string $name
     *
     * @return void
     */
    public function testDeleteProductPackagingUnitTypeShouldDeletePackagingUnitType(string $name): void
    {
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        $this->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Action
        $productPackagingUnitTypeDeleted = $this->getFacade()->deleteProductPackagingUnitType($productPackagingUnitTypeTransfer);
        $this->assertTrue($productPackagingUnitTypeDeleted);
        // Assert exception thrown
        $this->getFacade()->getProductPackagingUnitTypeById($productPackagingUnitTypeTransfer);
    }

    /**
     * @return array
     */
    public function getProductPackagingUnitTypeData(): array
    {
        return [
            [
                'packaging_unit_type.test1.name',
                (new ProductPackagingUnitTypeTranslationTransfer())
                    ->setLocaleCode('en_US')
                    ->setName('name1'),
                (new ProductPackagingUnitTypeTranslationTransfer())
                    ->setLocaleCode('de_DE')
                    ->setName('Name1'),
            ],
        ];
    }

    /**
     * @dataProvider getProductPackagingUnitTypeDataForNameChange
     *
     * @param string $name
     * @param string $newName
     *
     * @return void
     */
    public function testUpdateProductPackagingUnitTypeShouldUpdatePackagingUnitType(string $name, string $newName): void
    {
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())
            ->build()
            ->setName($name);

        $productPackagingUnitTypeTransfer = $this->getFacade()->createProductPackagingUnitType($productPackagingUnitTypeTransfer);

        // Action
        $productPackagingUnitTypeTransfer->setName($newName);
        $productPackagingUnitTypeTransfer = $this->getFacade()->updateProductPackagingUnitType($productPackagingUnitTypeTransfer);
        $this->assertEquals($productPackagingUnitTypeTransfer->getName(), $newName);
    }

    /**
     * @return array
     */
    public function getProductPackagingUnitTypeDataForNameChange(): array
    {
        return [
            [
                'packaging_unit_type.test1.name',
                'packaging_unit_type.test2.name',
            ],
        ];
    }

    /**
     * @return void
     */
    public function testExpandCartChangeWithAmountSalesUnit(): void
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::LEAD_PRODUCT_SKU => $itemProductConcreteTransfer->getSku(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        $code = 'MYCODE' . random_int(1, 100);
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $itemProductConcreteTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $productMeasurementSalesUnitEntityTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $boxProductConcreteTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())
            ->fromArray($productMeasurementSalesUnitEntityTransfer->toArray(), true);

        $cartChange = (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setId($boxProductConcreteTransfer->getIdProductConcrete())
                    ->setSku($boxProductConcreteTransfer->getSku())
                    ->setQuantity(static::ITEM_QUANTITY)
                    ->setAmount(static::PACKAGE_AMOUNT)
                    ->setAmountSalesUnit($productMeasurementSalesUnitTransfer)
            );

        $this->getFacade()->expandCartChangeWithAmountSalesUnit($cartChange);

        foreach ($cartChange->getItems() as $itemTransfer) {
            $this->assertInstanceOf(ProductMeasurementSalesUnitTransfer::class, $itemTransfer->getAmountSalesUnit());
        }
    }

    /**
     * @return void
     */
    public function testPreCheckCartAvailability(): void
    {
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote($this->createTestQuoteTransfer())
            ->addItem($this->createTestPackagingUnitItemTransfer());

        // Action
        $cartPreCheckResponseTransfer = $this->getFacade()->checkCartChangeAmountAvailability($cartChangeTransfer);
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutAvailabilityPreCheck(): void
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $quoteTransfer = $this->createTestQuoteTransfer()
            ->addItem($this->createTestPackagingUnitItemTransfer());

        // Action
        $this->getFacade()
            ->checkCheckoutAmountAvailability($quoteTransfer, $checkoutResponseTransfer);

        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateProductPackagingUnitLeadProductAvailability(): void
    {
        $boxProductConcreteTransfer = $this->createProductPackagingUnitProductConcrete();

        $this->getFacade()->updateLeadProductAvailability($boxProductConcreteTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testUpdateProductPackagingUnitLeadProductReservation(): void
    {
        $boxProductConcreteTransfer = $this->createProductPackagingUnitProductConcrete();

        $this->getFacade()->updateLeadProductReservation($boxProductConcreteTransfer->getSku());
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductPackagingUnitProductConcrete(): ProductConcreteTransfer
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::LEAD_PRODUCT_SKU => $itemProductConcreteTransfer->getSku(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        return $boxProductConcreteTransfer;
    }

    /**
     * @return void
     */
    public function testSetCustomAmountPrice(): void
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::LEAD_PRODUCT_SKU => $itemProductConcreteTransfer->getSku(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        $unitGrossPrice = 6000;

        $productPackagingUnitAmountTransfer = (new ProductPackagingUnitAmountTransfer())
            ->setIsVariable(true)
            ->setDefaultAmount(4);

        $productPackagingUnitTransfer = (new ProductPackagingUnitTransfer())
            ->setProductPackagingUnitAmount($productPackagingUnitAmountTransfer);

        $cartChange = (new CartChangeTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setPriceMode(static::PRICE_MODE_GROSS)
            )
            ->addItem(
                (new ItemTransfer())
                    ->setSku($boxProductConcreteTransfer->getSku())
                    ->setQuantity(1)
                    ->setAmount(6)
                    ->setProductPackagingUnit($productPackagingUnitTransfer)
                    ->setUnitGrossPrice($unitGrossPrice)
            );

        $this->getFacade()->setCustomAmountPrice($cartChange);

        foreach ($cartChange->getItems() as $itemTransfer) {
            $this->assertNotEquals($itemTransfer->getUnitGrossPrice(), $unitGrossPrice);
            $this->assertEquals($itemTransfer->getUnitGrossPrice(), 9000);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createTestQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setStore(
                (new StoreTransfer())
                    ->setIdStore(1)
                    ->setName('DE')
            );
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createTestPackagingUnitItemTransfer(): ItemTransfer
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::LEAD_PRODUCT_SKU => $itemProductConcreteTransfer->getSku(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        return (new ItemTransfer())
            ->setQuantity(static::ITEM_QUANTITY)
            ->setId($boxProductConcreteTransfer->getIdProductConcrete())
            ->setSku($boxProductConcreteTransfer->getSku())
            ->setAmount(static::PACKAGE_AMOUNT)
            ->setAmountLeadProduct(
                (new ProductPackagingLeadProductTransfer())
                    ->setProduct($boxProductConcreteTransfer)
            );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testExpandCartChangeGroupKeyWithAmountSalesUnitNoSalesUnitIsDefined(): void
    {
        // Assign
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithountAmountSalesUnitForGroupKeyGeneration(static::GROUP_KEY, static::PACKAGE_AMOUNT, static::ITEM_QUANTITY);

        // Act
        $cartChangeTransfer = $this->getFacade()->expandCartChangeGroupKeyWithAmount($cartChangeTransfer);
        $itemTransfer = $cartChangeTransfer->getItems()[0];

        // Assert
        $this->assertSame(static::GROUP_KEY, $itemTransfer->getGroupKey());
    }

    /**
     * @return void
     */
    public function testExpandCartChangeGroupKeyWithAmountSalesUnitIfSalesUnitIsDefined(): void
    {
        // Assign
        $expectedGroupKey = sprintf(static::GROUP_KEY_FORMAT, static::GROUP_KEY, static::ITEM_QUANTITY, static::SALES_UNIT_ID);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithAmountSalesUnitForGroupKeyGeneration(static::GROUP_KEY, static::PACKAGE_AMOUNT, static::ITEM_QUANTITY, static::SALES_UNIT_ID);

        // Act
        $cartChangeTransfer = $this->getFacade()->expandCartChangeGroupKeyWithAmount($cartChangeTransfer);
        $itemTransfer = $cartChangeTransfer->getItems()[0];

        // Assert
        $this->assertSame($expectedGroupKey, $itemTransfer->getGroupKey());
    }

    /**
     * @dataProvider calculateAmountNormalizedSalesUnitValues
     *
     * @param int $amount
     * @param int $quantity
     * @param float $conversion
     * @param int $precision
     * @param int $expectedResult
     *
     * @return void
     */
    public function testCalculateAmountNormalizedSalesUnitValueCalculatesCorrectValues(int $amount, int $quantity, float $conversion, int $precision, int $expectedResult): void
    {
        // Assign
        $quoteTransfer = $this->tester->createQuoteTransferForValueCalculation($amount, $quantity, $conversion, $precision);

        // Act
        $updatedQuoteTransfer = $this->getFacade()->calculateAmountSalesUnitValueInQuote($quoteTransfer);

        // Assert
        $itemTransfer = $updatedQuoteTransfer->getItems()[0];
        $this->assertSame($expectedResult, $itemTransfer->getAmountSalesUnit()->getValue());
    }

    /**
     * @return array
     */
    public function calculateAmountNormalizedSalesUnitValues(): array
    {
        return [
            [7, 1, 1.25, 1000, 5600],
            [7, 1, 1.25, 100, 560],
            [7, 1, 1.25, 10, 56],
            [7, 1, 1.25, 1, 6],
            [10, 1, 5, 1, 2],
            [13, 1, 7, 1000, 1857],
            [13, 1, 7, 100, 186],
            [13, 1, 7, 10, 19],
            [13, 1, 7, 1, 2],
        ];
    }

    /**
     * @dataProvider itemAdditionAmounts
     *
     * @param bool $expectedIsSuccess
     * @param int $defaultAmount
     * @param int $quoteAmount
     * @param int $quoteQuantity
     * @param int|null $minRestriction
     * @param int|null $maxRestriction
     * @param int|null $intervalRestriction
     * @param bool $isVariable
     *
     * @return void
     */
    public function testValidateItemAddAmountRestrictions(
        bool $expectedIsSuccess,
        int $defaultAmount,
        int $quoteAmount,
        int $quoteQuantity,
        ?int $minRestriction,
        ?int $maxRestriction,
        ?int $intervalRestriction,
        bool $isVariable
    ): void {
        // Assign
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([
            SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE,
        ]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::LEAD_PRODUCT_SKU => $itemProductConcreteTransfer->getSku(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => $defaultAmount,
            SpyProductPackagingUnitEntityTransfer::AMOUNT_MIN => $minRestriction,
            SpyProductPackagingUnitEntityTransfer::AMOUNT_MAX => $maxRestriction,
            SpyProductPackagingUnitEntityTransfer::AMOUNT_INTERVAL => $intervalRestriction,
            SpyProductPackagingUnitEntityTransfer::IS_VARIABLE => $isVariable,
        ]);

        $code = 'MYCODE' . random_int(1, 100);
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $itemProductConcreteTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $productMeasurementSalesUnitEntityTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $boxProductConcreteTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())
            ->fromArray($productMeasurementSalesUnitEntityTransfer->toArray(), true);

        $cartChangeTransfer = $this->tester->createCartChangeTransferForProductPackagingUnitValidation($boxProductConcreteTransfer, $productMeasurementSalesUnitTransfer, $quoteAmount, $quoteQuantity);

        // Act
        $cartPreCheckResponseTransfer = $this->getFacade()->validateItemAddAmountRestrictions($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedIsSuccess, $cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return array
     */
    public function itemAdditionAmounts(): array
    {
        return [
            [true, 1, 2, 1, 1, null, 1, true], // general rule
            [true, 1, 7, 1, 7, null, 1, true], // min equals new amount
            [true, 1, 5, 1, 5, 5,    1, true], // max equals new amount
            [true, 1, 7, 1, 0, null, 7, true], // interval matches new amount
            [false, 1, 5, 1, 7, 7,    7, true], // min, max, interval matches new amount
            [false, 1, 5, 1, 8, null, 1, true], // min above new amount
            [false, 1, 5, 1, 1, 3,    1, true], // max below new amount
            [false, 1, 5, 1, 1, null, 3, true], // interval does not match new amount
        ];
    }

    /**
     * @return void
     */
    public function testExpandOrderItemWithAmountSalesUnit(): void
    {
        // Assign
        $code = 'MYCODE' . random_int(1, 100);
        $productTransfer = $this->tester->haveProduct();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);
        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        $productMeasurementSalesUnitTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $productTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $amountSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())->fromArray($productMeasurementSalesUnitTransfer->toArray(), true);
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(static::ITEM_QUANTITY);
        $itemTransfer->setAmountSalesUnit($amountSalesUnitTransfer);

        //Act
        $salesOrderItemEntity = $this->getFacade()->expandSalesOrderItemWithAmountSalesUnit(
            $itemTransfer,
            new SpySalesOrderItemEntityTransfer()
        );

        //Assert
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getAmountMeasurementUnitName());
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getAmountBaseMeasurementUnitName());
        $this->assertSame($amountSalesUnitTransfer->getPrecision(), $salesOrderItemEntity->getAmountMeasurementUnitPrecision());
        $this->assertSame($amountSalesUnitTransfer->getConversion(), $salesOrderItemEntity->getAmountMeasurementUnitConversion());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemWithAmountAndAmountSku(): void
    {
        // Assign
        $itemTransfer = $this->createTestPackagingUnitItemTransfer();

        //Act
        $salesOrderItemEntity = $this->getFacade()->expandSalesOrderItemWithAmountAndAmountSku(
            $itemTransfer,
            new SpySalesOrderItemEntityTransfer()
        );

        //Assert
        $this->assertSame($itemTransfer->getAmount()->toString(), $salesOrderItemEntity->getAmount()->toString());
        $this->assertSame($itemTransfer->getAmountLeadProduct()->getProduct()->getSku(), $salesOrderItemEntity->getAmountSku());
    }

    /**
     * @return void
     */
    public function testHydrateOrderWithAmountSalesUnit(): void
    {
        // Assign
        $salesOrderEntity = $this->tester->create();
        $productMeasurementUnit = $this->tester->haveProductMeasurementUnit();
        foreach ($salesOrderEntity->getItems() as $salesOrderItem) {
            $salesOrderItem->setAmountMeasurementUnitName($productMeasurementUnit->getName());
            $salesOrderItem->save();
        }

        $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);
        foreach ($salesOrderEntity->getItems() as $salesOrderItem) {
            $itemTransfer = (new ItemTransfer())->fromArray($salesOrderItem->toArray(), true);
            $orderTransfer->addItem($itemTransfer);
        }

        //Act
        $orderTransfer = $this->getFacade()->expandOrderWithAmountSalesUnit($orderTransfer);

        //Assert
        $this->assertInstanceOf(OrderTransfer::class, $orderTransfer);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $this->assertInstanceOf(ProductMeasurementSalesUnitTransfer::class, $itemTransfer->getAmountSalesUnit());
        }
    }

    /**
     * @return void
     */
    public function testExpandOrderWithAmountLeadProduct(): void
    {
        // Assign
        $testStateMachineProcessName = 'Test01';

        $this->tester->configureTestStateMachine([$testStateMachineProcessName]);

        $productTransfer = $this->tester->haveProduct();

        $savedOrderTransfer = $this->tester->haveOrder([
            'unitPrice' => 100,
            'sumPrice' => 100,
            'amountSku' => $productTransfer->getSku(),
            'amount' => static::AMOUNT_VALUE,
        ], $testStateMachineProcessName);

        $orderTransfer = (new OrderTransfer())->fromArray($savedOrderTransfer->toArray(), true);

        //Act
        $orderTransfer = $this->getFacade()->expandOrderWithAmountLeadProduct($orderTransfer);

        //Assert
        $this->assertInstanceOf(OrderTransfer::class, $orderTransfer);
    }

    /**
     * @return void
     */
    public function testDefaultProductPackagingUnitTypeName(): void
    {
        // Assign
        $configDefaultProductPackagingUnitTypMockeName = $this->getConfigStub()->getDefaultProductPackagingUnitTypeName();

        //Act
        $defaultProductPackagingUnitTypeName = $this->getFacade()->getDefaultProductPackagingUnitTypeName();

        //Assert
        $this->assertSame($configDefaultProductPackagingUnitTypMockeName, $defaultProductPackagingUnitTypeName);
    }

    /**
     * @return object|\Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig
     */
    protected function getConfigStub()
    {
        return Stub::make(ProductPackagingUnitConfig::class, [
            'getDefaultProductPackagingUnitTypeName' => function () {
                return static::DEFAULT_PRODUCT_PACKAGING_UNIT_TYPE_NAME;
            },
        ]);
    }

    /**
     * @return void
     */
    public function testCountProductPackagingUnitsByTypeId(): void
    {
        // Assign
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitTypeEntityTransfer = $this->tester->haveProductPackagingUnitType([
            SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE,
        ]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitTypeEntityTransfer->getIdProductPackagingUnitType(),
        ]);

        $boxProductPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeTransfer())->fromArray($boxProductPackagingUnitTypeEntityTransfer->toArray(), true);

        //Act
        $productPackagingUnitTypeCount = $this->getFacade()->countProductPackagingUnitsByTypeId($boxProductPackagingUnitTypeTransfer);

        //Assert
        $this->assertSame($productPackagingUnitTypeCount, 1);
    }

    /**
     * @return void
     */
    public function testGetInfrastructuralProductPackagingUnitTypeNames(): void
    {
        //Act
        $infrastructuralProductPackagingUnitTypeNames = $this->getFacade()->getInfrastructuralProductPackagingUnitTypeNames();

        //Assert
        $this->assertCount(1, $infrastructuralProductPackagingUnitTypeNames);
    }

    /**
     * @return void
     */
    public function testFindProductIdsByProductPackagingUnitTypeIds(): void
    {
        // Assign
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitTypeEntityTransfer = $this->tester->haveProductPackagingUnitType([
            SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE,
        ]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitTypeEntityTransfer->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::LEAD_PRODUCT_SKU => $itemProductConcreteTransfer->getSku(),
        ]);

        //Act
        $productAbstractIds = $this->getFacade()->findProductIdsByProductPackagingUnitTypeIds([$boxProductPackagingUnitTypeEntityTransfer->getIdProductPackagingUnitType()]);

        //Assert
        $this->assertCount(1, $productAbstractIds);
    }

    /**
     * @return void
     */
    public function testExpandCartChangeWithProductPackagingUnit(): void
    {
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::LEAD_PRODUCT_SKU => $itemProductConcreteTransfer->getSku(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        $cartChange = (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setId($boxProductConcreteTransfer->getIdProductConcrete())
                    ->setSku($boxProductConcreteTransfer->getSku())
                    ->setQuantity(static::ITEM_QUANTITY)
                    ->setAmount(static::PACKAGE_AMOUNT)
            );

        $this->getFacade()->expandCartChangeWithProductPackagingUnit($cartChange);

        foreach ($cartChange->getItems() as $itemTransfer) {
            $this->assertInstanceOf(ProductPackagingLeadProductTransfer::class, $itemTransfer->getAmountLeadProduct());
            $this->assertEquals($itemProductConcreteTransfer->getSku(), $itemTransfer->getAmountLeadProduct()->getProduct()->getSku());
        }
    }

    /**
     * @return void
     */
    public function testTransformSplittableItem(): void
    {
        // Assign
        $this->setData(true);
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::CONCRETE_PRODUCT_SKU)
            ->setQuantity(2)
            ->setAmount(3)
            ->setAmountSalesUnit(new ProductMeasurementSalesUnitTransfer());

        //Act
        $itemCollectionTransfer = $this->getFacade()->transformSplittableItem($itemTransfer);

        //Assert
        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $this->assertSame(1, $itemTransfer->getQuantity());
            $this->assertSame('1.5', $itemTransfer->getAmount()->trim()->toString());
        }
    }

    /**
     * @return void
     */
    public function testIsItemQuantitySplittable(): void
    {
        // Assign
        $this->setData(true);
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::CONCRETE_PRODUCT_SKU)
            ->setAmountSalesUnit(new ProductMeasurementSalesUnitTransfer());

        //Act
        $isProductPackagingUnitItemQuantitySplittable = $this->getFacade()->isProductPackagingUnitItemQuantitySplittable($itemTransfer);

        //Assert
        $this->assertTrue($isProductPackagingUnitItemQuantitySplittable);
    }

    /**
     * @param bool $isQuantitySplittable
     *
     * @return void
     */
    protected function setData(bool $isQuantitySplittable): void
    {
        $productAbstract = SpyProductAbstractQuery::create()
            ->filterBySku(static::ABSTRACT_PRODUCT_SKU)
            ->findOne();

        if ($productAbstract === null) {
            $productAbstract = new SpyProductAbstract();

            $productAbstract
                ->setAttributes('{}')
                ->setSku(static::ABSTRACT_PRODUCT_SKU);
        }

        $productAbstract->save();

        $product = SpyProductQuery::create()
            ->filterBySku(static::CONCRETE_PRODUCT_SKU)
            ->findOne();

        if ($product === null) {
            $product = new SpyProduct();
            $product->setAttributes('{}')
                ->setSku(static::CONCRETE_PRODUCT_SKU);
        }

        $product
            ->setFkProductAbstract($productAbstract->getIdProductAbstract())
            ->setIsQuantitySplittable($isQuantitySplittable)
            ->save();
    }

    /**
     * @return void
     */
    public function testAddAndRemoveItemsToAndFromQuote(): void
    {
        $itemSku = 'sku_1';
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $itemTransfer = $this->tester->createProductPackagingUnitItemTransfer($itemSku);

        // Action
        $quoteTransfer = $this->getFacade()->addItemToQuote($itemTransfer, $quoteTransfer);

        //Assert
        $this->assertCount(1, $quoteTransfer->getItems());

        // Action
        $quoteTransfer = $this->getFacade()->addItemToQuote($itemTransfer, $quoteTransfer);

        //Assert
        $this->assertCount(1, $quoteTransfer->getItems());
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertEquals(2, $itemTransfer->getQuantity());
            $this->assertEquals('2', $itemTransfer->getAmount()->toString());
        }

        // Action
        $this->getFacade()->removeItemFromQuote(
            $this->tester->createProductPackagingUnitItemTransfer($itemSku),
            $quoteTransfer
        );

        //Assert
        $this->assertCount(1, $quoteTransfer->getItems());
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertEquals(1, $itemTransfer->getQuantity());
            $this->assertEquals('1', $itemTransfer->getAmount()->toString());
        }

        // Action
        $this->getFacade()->removeItemFromQuote(
            $this->tester->createProductPackagingUnitItemTransfer($itemSku),
            $quoteTransfer
        );

        //Assert
        $this->assertCount(0, $quoteTransfer->getItems());
    }
}
