<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Active\PreCheck;

use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Active\PreCheck\ProductBundleCartActiveCheck;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Active
 * @group PreCheck
 * @group ProductBundleCartActiveCheckTest
 * Add your own group annotations below this line
 */
class ProductBundleCartActiveCheckTest extends Unit
{
    protected const INACTIVE_PRODUCT_SKU = 'inactive';
    protected const ACTIVE_PRODUCT_SKU = 'active';

    /**
     * @return void
     */
    public function testShouldReturnErrorMessageIfBundleProductIsNotActive()
    {
        $productBundleCartActiveCheck = $this->createProductBundleCartActiveCheck();
        $cartPreCheckResponseTransfer = $productBundleCartActiveCheck->checkCartAvailability(
            $this->createCartChangeTransferWithProduct(static::INACTIVE_PRODUCT_SKU)
        );

        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testShouldReturnNoMessagesIfBundleProductIsActive()
    {
        $productBundleCartActiveCheck = $this->createProductBundleCartActiveCheck();
        $cartPreCheckResponseTransfer = $productBundleCartActiveCheck->checkCartAvailability(
            $this->createCartChangeTransferWithProduct(static::ACTIVE_PRODUCT_SKU)
        );

        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithProduct(string $sku): CartChangeTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($sku);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($itemTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Active\PreCheck\ProductBundleCartActiveCheckInterface
     */
    protected function createProductBundleCartActiveCheck()
    {
        return Stub::construct(ProductBundleCartActiveCheck::class, [
            $this->createProductRepositoryStub(),
        ]);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected function createProductRepositoryStub()
    {
        return Stub::make(ProductBundleRepository::class, [
            'findBundledProductsBySku' => function (string $sku): array {
                return $this->getBundledProductBySku($sku);
            },
        ]);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    protected function getBundledProductBySku(string $sku): array
    {
        $productEntityTransfer = new SpyProductEntityTransfer();
        $productEntityTransfer->setSku($sku);
        $productEntityTransfer->setIsActive($sku === static::ACTIVE_PRODUCT_SKU);

        return [$productEntityTransfer];
    }
}
