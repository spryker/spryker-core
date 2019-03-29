<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantity\Business;

use Codeception\Test\Unit;
use Spryker\Zed\ProductQuantity\Business\ProductQuantityFacadeInterface;

/**
 * Auto-generated group annotations
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
     * @var \Spryker\Zed\ProductQuantity\Business\ProductQuantityFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected $productQuantityFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productQuantityFacade = $this->createProductQuantityFacade();
    }

    /**
     * @return \Spryker\Zed\ProductQuantity\Business\ProductQuantityFacadeInterface
     */
    protected function createProductQuantityFacade(): ProductQuantityFacadeInterface
    {
        return $this->tester->getFacade();
    }

    /**
     * @dataProvider itemRemovalQuantities
     *
     * @param bool $expectedIsSuccess
     * @param float $quoteQuantity
     * @param float $changeQuantity
     * @param int|null $minRestriction
     * @param int|null $maxRestriction
     * @param int|null $intervalRestriction
     *
     * @return void
     */
    public function testValidateItemRemoveProductQuantityRestrictionsValidatesProductsWithProductQuantityRestrictions(
        $expectedIsSuccess,
        $quoteQuantity,
        $changeQuantity,
        $minRestriction,
        $maxRestriction,
        $intervalRestriction
    ) {
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
    public function itemRemovalQuantities()
    {
        return [
            'general rule int stock' => [true, 5, 2, 1, null, 1],
            'general rule float stock' => [true, 5.5, 2.5, 1, null, 1],
            'min equals new int quantity' => [true, 5, 2, 3, null, 1],
            'min equals new float quantity' => [true, 5.5, 2.5, 3, null, 1],
            'max equals new int quantity' => [true, 5, 2, 1, 3,    1],
            'max equals new float quantity' => [true, 5.5, 2.5, 1, 3, 1],
            'shifted interval matches new int quantity' => [true, 5, 2, 1, null, 2],
            'shifted interval matches new float quantity' => [true, 5.5, 2.5, 1, null, 2],
            'interval matches new int quantity' => [true, 5, 2, 0, null, 3],
            'interval matches new float quantity' => [true, 5.5, 2.5, 0, null, 3],
            'min, max, interval matches new int quantity' => [true, 5, 2, 3, 3,    3],
            'min, max, interval matches new float quantity' => [true, 5.5, 2.5, 3, 3,    3],
            'can remove all items regardless rules int stock' => [true, 5, 5, 2, 4,    3],
            'can remove all items regardless rules float stock' => [true, 5.5, 5.5, 2, 4,    3],
            'general false rule int stock' => [false, 5, 6, 1, null, 1],
            'general false rule float stock' => [false, 5.5, 6.5, 1, null, 1],
            'min above new int quantity' => [false, 5, 2, 4, null, 1],
            'min above new float quantity' => [false, 5.5, 2.5, 4, null, 1],
            'max below new int quantity' => [false, 5, 2, 1, 2,    1],
            'max below new float quantity' => [false, 5.5, 2.5, 1, 2,    1],
            'shifted interval does not match new int quantity' => [false, 5, 2, 1, null, 3],
            'shifted interval does not match new float quantity' => [false, 5.5, 2.5, 1, null, 3],
            'interval does not match new int quantity' => [false, 5, 2, 0, null, 2],
            'interval does not match new float quantity' => [false, 5.5, 2.5, 0, null, 2],
            'empty quote int stock' => [false, 0, 1, 1, null, 1],
            'empty quote float stock' => [false, 0, 1.5, 1, null, 1],
        ];
    }

    /**
     * @dataProvider itemRemovalProductsWithoutProductQuantity
     *
     * @param bool $expectedIsSuccess
     * @param float $quoteQuantity
     * @param float $changeQuantity
     *
     * @return void
     */
    public function testValidateItemRemoveProductQuantityRestrictionsValidatesProductsWithoutProductQuantityRestrictions(
        $expectedIsSuccess,
        $quoteQuantity,
        $changeQuantity
    ) {
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
    public function itemRemovalProductsWithoutProductQuantity()
    {
        return [
            [true,  5, 4],
            [true,  5.5, 4.5],
            [true,  5, 5],
            [true,  5.5, 5.5],
            [false, 0, 1],
            [false, 0, 1.5],
            [false, 5, 6],
            [false, 5.5, 6.5],
        ];
    }

    /**
     * @dataProvider itemAdditionQuantities
     *
     * @param bool $expectedIsSuccess
     * @param float $quoteQuantity
     * @param float $changeQuantity
     * @param int|null $minRestriction
     * @param int|null $maxRestriction
     * @param int|null $intervalRestriction
     *
     * @return void
     */
    public function testValidateItemAddProductQuantityRestrictionsValidatesProductsWithProductQuantityRestrictions(
        $expectedIsSuccess,
        $quoteQuantity,
        $changeQuantity,
        $minRestriction,
        $maxRestriction,
        $intervalRestriction
    ) {
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
    public function itemAdditionQuantities()
    {
        return [
            'general rule int stock' => [true, 5, 2, 1, null, 1],
            'general rule float stock' => [true, 5.5, 2.5, 1, null, 1],
            'min equals new int quantity' => [true, 5, 2, 7, null, 1],
            'min equals new float quantity' => [true, 5.5, 2.5, 8, null, 1],
            'max equals new int quantity' => [true, 5, 2, 7, 7,    1],
            'max equals new float quantity' => [true, 5.5, 2.5, 7, 8,    1],
            'shifted interval matches new int quantity' => [true, 5, 2, 7, null, 2],
            'shifted interval matches new float quantity' => [true, 5.5, 2.5, 8, null, 2],
            'interval matches new int quantity' => [true, 5, 2, 0, null, 7],
            'interval matches new float quantity' => [true, 5.5, 2.5, 0, null, 8],
            'min, max, interval matches new int quantity' => [true, 5, 2, 7, 7,    7],
            'min, max, interval matches new float quantity' => [true, 5.5, 2.5, 8, 8,    8],
            'empty quote int quantity' => [true, 0, 1, 1, null, 1],
            'empty quote float quantity' => [true, 0, 1.5, 1, null, 1],

            'general rule 0 qty' => [false, 0, 0, 1, null, 1],
            'general rule negative int qty' => [false, 0, -4, 1, null, 1],
            'general rule negative float qty' => [false, 0, -4.5, 1, null, 1],
            'min above new int quantity' => [false, 5, 2, 8, null, 1],
            'min above new float quantity' => [false, 5.5, 2.3, 8, null, 1],
            'max below new int quantity' => [false, 5, 2, 1, 6,    1],
            'max below new float quantity' => [false, 5.5, 2.5, 1, 6,    1],
            'shifted interval does not match new int quantity' => [false, 5, 2, 1, null, 4],
            'shifted interval does not match new float quantity' => [false, 5.5, 2.5, 1, null, 4],
            'interval does not match new int quantity' => [false, 5, 2, 0, null, 2],
            'interval does not match new float quantity' => [false, 5.5, 2.5, 0, null, 3],
        ];
    }

    /**
     * @dataProvider itemAdditionProductsWithoutProductQuantity
     *
     * @param bool $expectedIsSuccess
     * @param float $quoteQuantity
     * @param float $changeQuantity
     *
     * @return void
     */
    public function testValidateItemAddProductQuantityRestrictionsValidatesProductsWithoutProductQuantityRestrictions(
        $expectedIsSuccess,
        $quoteQuantity,
        $changeQuantity
    ) {
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
    public function itemAdditionProductsWithoutProductQuantity()
    {
        return [
            [true, 0, 1],
            [true, 0, 1.5],
            [true, 2, 4],
            [true, 2.5, 4.5],
            [false, 0, 0],
            [false, 0, -1],
            [false, 0, -1.5],
        ];
    }

    /**
     * @return void
     */
    public function testFindProductQuantityTransfersByProductIdsFindsAllExistingItems()
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
    public function testFindProductQuantityTransfersByProductIdsReturnsEmptyArrayWhenProductsWereNotFound()
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
    public function testHasCartChangeTransferNormalizableItemsShouldReturnTrue(): void
    {
        $normalizableField = 'quantity';

        $cartChangeTransfer = $this->tester->createEmptyCartChangeTransfer();
        $cartChangeTransfer = $this->tester->addEmptyItemTransferToCartChangeTransfer($cartChangeTransfer);
        $cartChangeTransfer->getItems()[0]->addNormalizableField($normalizableField);

        $actualResult = $this->productQuantityFacade->hasCartChangeTransferNormalizableItems($cartChangeTransfer, [$normalizableField]);

        $this->assertEquals(true, $actualResult);
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

        $this->assertEquals(false, $actualResult);
    }
}
