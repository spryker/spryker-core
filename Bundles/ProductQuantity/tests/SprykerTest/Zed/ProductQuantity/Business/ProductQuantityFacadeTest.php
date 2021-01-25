<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantity\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductQuantity
 * @group Business
 * @group Facade
 * @group ProductQuantityFacadeTest
 * Add your own group annotations below this line
 */
class ProductQuantityFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductQuantity\ProductQuantityBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductQuantity\Business\ProductQuantityFacadeInterface
     */
    protected $productQuantityFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productQuantityFacade = $this->tester->getLocator()->productQuantity()->facade();
    }

    /**
     * @dataProvider itemRemovalQuantities
     *
     * @param bool $expectedIsSuccess
     * @param int $quoteQuantity
     * @param int $changeQuantity
     * @param int|null $minRestriction
     * @param int|null $maxRestriction
     * @param int|null $intervalRestriction
     *
     * @return void
     */
    public function testValidateItemRemoveProductQuantityRestrictionsValidatesProductsWithProductQuantityRestrictions(
        bool $expectedIsSuccess,
        int $quoteQuantity,
        int $changeQuantity,
        ?int $minRestriction,
        ?int $maxRestriction,
        ?int $intervalRestriction
    ): void {
        // Assign
        $productTransfer = $this->tester->createProductWithSpecificProductQuantity($minRestriction, $maxRestriction, $intervalRestriction);

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        if ($quoteQuantity > 0) {
            $this->tester->addSkuToCartChangeTransferQuote($cartChangeTransfer, $productTransfer->getSku(), $quoteQuantity);
        }
        $this->tester->addSkuToCartChangeTransfer($cartChangeTransfer, $productTransfer->getSku(), $changeQuantity);

        // Act
        $cartPreCheckResponseTransfer = $this->productQuantityFacade->validateItemRemoveProductQuantityRestrictions($cartChangeTransfer);
        $actualIsSuccess = $cartPreCheckResponseTransfer->getIsSuccess();

        // Assert
        $this->assertSame($expectedIsSuccess, $actualIsSuccess);
    }

    /**
     * @return array
     */
    public function itemRemovalQuantities(): array
    {
        return [
            [true, 5, 2, 1, null, 1], // general rule
            [true, 5, 2, 3, null, 1], // min equals new quantity
            [true, 5, 2, 1, 3,    1], // max equals new quantity
            [true, 5, 2, 1, null, 2], // shifted interval matches new quantity
            [true, 5, 2, 0, null, 3], // interval matches new quantity
            [true, 5, 2, 3, 3,    3], // min, max, interval matches new quantity
            [true, 5, 5, 2, 4,    3], // can remove all items regardless rules

            [false, 5, 6, 1, null, 1], // general rule
            [false, 5, 2, 4, null, 1], // min above new quantity
            [false, 5, 2, 1, 2,    1], // max below new quantity
            [false, 5, 2, 1, null, 3], // shifted interval does not match new quantity
            [false, 5, 2, 0, null, 2], // interval does not match new quantity
            [false, 0, 1, 1, null, 1], // empty quote
        ];
    }

    /**
     * @dataProvider itemRemovalProductsWithoutProductQuantity
     *
     * @param bool $expectedIsSuccess
     * @param int $quoteQuantity
     * @param int $changeQuantity
     *
     * @return void
     */
    public function testValidateItemRemoveProductQuantityRestrictionsValidatesProductsWithoutProductQuantityRestrictions(
        bool $expectedIsSuccess,
        int $quoteQuantity,
        int $changeQuantity
    ): void {
        // Assign
        $productTransfer = $this->tester->haveProduct();

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        if ($quoteQuantity > 0) {
            $this->tester->addSkuToCartChangeTransferQuote($cartChangeTransfer, $productTransfer->getSku(), $quoteQuantity);
        }
        $this->tester->addSkuToCartChangeTransfer($cartChangeTransfer, $productTransfer->getSku(), $changeQuantity);

        // Act
        $cartPreCheckResponseTransfer = $this->productQuantityFacade->validateItemRemoveProductQuantityRestrictions($cartChangeTransfer);
        $actualIsSuccess = $cartPreCheckResponseTransfer->getIsSuccess();

        // Assert
        $this->assertSame($expectedIsSuccess, $actualIsSuccess);
    }

    /**
     * @return array
     */
    public function itemRemovalProductsWithoutProductQuantity(): array
    {
        return [
            [true,  5, 4],
            [true,  5, 5],
            [false, 0, 1],
            [false, 5, 6],
        ];
    }

    /**
     * @dataProvider itemAdditionQuantities
     *
     * @param bool $expectedIsSuccess
     * @param int $quoteQuantity
     * @param int $changeQuantity
     * @param int|null $minRestriction
     * @param int|null $maxRestriction
     * @param int|null $intervalRestriction
     *
     * @return void
     */
    public function testValidateItemAddProductQuantityRestrictionsValidatesProductsWithProductQuantityRestrictions(
        bool $expectedIsSuccess,
        int $quoteQuantity,
        int $changeQuantity,
        ?int $minRestriction,
        ?int $maxRestriction,
        ?int $intervalRestriction
    ): void {
        // Assign
        $productTransfer = $this->tester->createProductWithSpecificProductQuantity($minRestriction, $maxRestriction, $intervalRestriction);

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        if ($quoteQuantity > 0) {
            $this->tester->addSkuToCartChangeTransferQuote($cartChangeTransfer, $productTransfer->getSku(), $quoteQuantity);
        }
        $this->tester->addSkuToCartChangeTransfer($cartChangeTransfer, $productTransfer->getSku(), $changeQuantity);

        // Act
        $cartPreCheckResponseTransfer = $this->productQuantityFacade->validateItemAddProductQuantityRestrictions($cartChangeTransfer);
        $actualIsSuccess = $cartPreCheckResponseTransfer->getIsSuccess();

        // Assert
        $this->assertSame($expectedIsSuccess, $actualIsSuccess);
    }

    /**
     * @return array
     */
    public function itemAdditionQuantities(): array
    {
        return [
            [true, 5, 2, 1, null, 1], // general rule
            [true, 5, 2, 7, null, 1], // min equals new quantity
            [true, 5, 2, 7, 7,    1], // max equals new quantity
            [true, 5, 2, 7, null, 2], // shifted interval matches new quantity
            [true, 5, 2, 0, null, 7], // interval matches new quantity
            [true, 5, 2, 7, 7,    7], // min, max, interval matches new quantity
            [true, 0, 1, 1, null, 1], // empty quote

            [false, 0, 0, 1, null, 1], // general rule 0 qty
            [false, 0, -4, 1, null, 1], // general rule negative qty
            [false, 5, 2, 8, null, 1], // min above new quantity
            [false, 5, 2, 1, 6,    1], // max below new quantity
            [false, 5, 2, 1, null, 4], // shifted interval does not match new quantity
            [false, 5, 2, 0, null, 2], // interval does not match new quantity
        ];
    }

    /**
     * @dataProvider itemAdditionProductsWithoutProductQuantity
     *
     * @param bool $expectedIsSuccess
     * @param int $quoteQuantity
     * @param int $changeQuantity
     *
     * @return void
     */
    public function testValidateItemAddProductQuantityRestrictionsValidatesProductsWithoutProductQuantityRestrictions(
        bool $expectedIsSuccess,
        int $quoteQuantity,
        int $changeQuantity
    ): void {
        // Assign
        $productTransfer = $this->tester->haveProduct();

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        if ($quoteQuantity > 0) {
            $this->tester->addSkuToCartChangeTransferQuote($cartChangeTransfer, $productTransfer->getSku(), $quoteQuantity);
        }
        $this->tester->addSkuToCartChangeTransfer($cartChangeTransfer, $productTransfer->getSku(), $changeQuantity);

        // Act
        $cartPreCheckResponseTransfer = $this->productQuantityFacade->validateItemAddProductQuantityRestrictions($cartChangeTransfer);
        $actualIsSuccess = $cartPreCheckResponseTransfer->getIsSuccess();

        // Assert
        $this->assertSame($expectedIsSuccess, $actualIsSuccess);
    }

    /**
     * @return array
     */
    public function itemAdditionProductsWithoutProductQuantity(): array
    {
        return [
            [true, 0, 1],
            [true, 2, 4],
            [false, 0, 0],
            [false, 0, -1],
        ];
    }

    /**
     * @return void
     */
    public function testFindProductQuantityTransfersByProductIdsFindsAllExistingItems(): void
    {
        // Assign
        $productIds = [
            $this->tester->createProductWithProductQuantity()->getIdProductConcrete(),
            $this->tester->createProductWithProductQuantity()->getIdProductConcrete(),
        ];
        $expectedCount = count($productIds);

        // Act
        $productQuantityTransfers = $this->productQuantityFacade->findProductQuantityTransfersByProductIds($productIds);
        $actualCount = count($productQuantityTransfers);

        // Assert
        $this->assertSame($expectedCount, $actualCount);
    }

    /**
     * @return void
     */
    public function testFindProductQuantityTransfersByProductIdsReturnsEmptyArrayWhenProductsWereNotFound(): void
    {
        // Assign
        $dummyProductIds = [999999991, 999999992];
        $expectedCount = 0;

        // Act
        $productQuantityTransfers = $this->productQuantityFacade->findProductQuantityTransfersByProductIds($dummyProductIds);
        $actualCount = count($productQuantityTransfers);

        // Assert
        $this->assertSame($expectedCount, $actualCount);
    }

    /**
     * @return void
     */
    public function testNormalizeCartChangeTransferMinimumAdjustment(): void
    {
        $expectedQuantity = 2;
        $productTransfer = $this->tester->createProductWithSpecificProductQuantity($expectedQuantity, 100, 2);
        $expectedSku = $productTransfer->getSku();

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        $cartChangeTransfer = $this->tester->addSkuToCartChangeTransfer($cartChangeTransfer, $expectedSku, 1);
        $cartChangeTransfer->getItems()[0]->addNormalizableField('quantity');

        $cartChangeTransfer = $this->productQuantityFacade->normalizeCartChangeTransferItems($cartChangeTransfer);

        $this->assertSame($expectedQuantity, $cartChangeTransfer->getItems()[0]->getQuantity());
        $this->assertSame($expectedSku, $cartChangeTransfer->getItems()[0]->getSku());
    }

    /**
     * @return void
     */
    public function testNormalizeCartChangeTransferMaximumAdjustment(): void
    {
        $expectedQuantity = 100;
        $productTransfer = $this->tester->createProductWithSpecificProductQuantity(2, $expectedQuantity, 2);
        $expectedSku = $productTransfer->getSku();

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        $cartChangeTransfer = $this->tester->addSkuToCartChangeTransfer($cartChangeTransfer, $expectedSku, 200);
        $cartChangeTransfer->getItems()[0]->addNormalizableField('quantity');

        $cartChangeTransfer = $this->productQuantityFacade->normalizeCartChangeTransferItems($cartChangeTransfer);

        $this->assertSame($expectedQuantity, $cartChangeTransfer->getItems()[0]->getQuantity());
        $this->assertSame($expectedSku, $cartChangeTransfer->getItems()[0]->getSku());
    }

    /**
     * @return void
     */
    public function testNormalizeCartChangeTransferStepAdjustment(): void
    {
        $expectedQuantity = 4;
        $productTransfer = $this->tester->createProductWithSpecificProductQuantity(2, 100, 2);
        $expectedSku = $productTransfer->getSku();

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        $cartChangeTransfer = $this->tester->addSkuToCartChangeTransfer($cartChangeTransfer, $expectedSku, 3);
        $cartChangeTransfer->getItems()[0]->addNormalizableField('quantity');

        $cartChangeTransfer = $this->productQuantityFacade->normalizeCartChangeTransferItems($cartChangeTransfer);

        $this->assertSame($expectedQuantity, $cartChangeTransfer->getItems()[0]->getQuantity());
        $this->assertSame($expectedSku, $cartChangeTransfer->getItems()[0]->getSku());
    }

    /**
     * @return void
     */
    public function testNormalizeCartChangeTransferItemsWillNormalizeQuantityWhenQuoteAlreadyHaveSameItem(): void
    {
        //Arrange
        $expectedQuantity = 10;
        $productTransfer = $this->tester->createProductWithSpecificProductQuantity(10, null, 10);

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        $cartChangeTransfer->getQuote()->addItem($this->tester->createItemTransferWithNormalizableQuantity(
            $productTransfer->getSku(),
            $productTransfer->getSku(),
            60
        ));
        $cartChangeTransfer->addItem($this->tester->createItemTransferWithNormalizableQuantity(
            $productTransfer->getSku(),
            $productTransfer->getSku(),
            8
        ));

        //Act
        $cartChangeTransfer = $this->productQuantityFacade->normalizeCartChangeTransferItems($cartChangeTransfer);

        //Assert
        $this->assertSame(
            $expectedQuantity,
            $cartChangeTransfer->getItems()[0]->getQuantity(),
            'Item quantity does not match expected value.'
        );
    }

    /**
     * @return void
     */
    public function testHasCartChangeTransferNormalizableItemsShouldReturnTrue(): void
    {
        $normalizableField = 'quantity';

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        $cartChangeTransfer = $this->tester->addEmptyItemTransferToCartChangeTransfer($cartChangeTransfer);
        $cartChangeTransfer->getItems()[0]->addNormalizableField($normalizableField);

        $actualResult = $this->productQuantityFacade->hasCartChangeTransferNormalizableItems($cartChangeTransfer, [$normalizableField]);

        $this->assertTrue($actualResult);
    }

    /**
     * @return void
     */
    public function testHasCartChangeTransferNormalizableItemsShouldReturnFalse(): void
    {
        $normalizableField = 'quantity';

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        $cartChangeTransfer = $this->tester->addEmptyItemTransferToCartChangeTransfer($cartChangeTransfer);

        $actualResult = $this->productQuantityFacade->hasCartChangeTransferNormalizableItems($cartChangeTransfer, [$normalizableField]);

        $this->assertFalse($actualResult);
    }
}
