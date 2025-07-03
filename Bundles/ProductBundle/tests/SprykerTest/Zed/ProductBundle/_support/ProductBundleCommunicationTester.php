<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductBundle\PHPMD)
 */
class ProductBundleCommunicationTester extends Actor
{
    use _generated\ProductBundleCommunicationTesterActions;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @param bool $isProductActive
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProductBundle(bool $isProductActive = false, string $sku = 'sku1'): ProductConcreteTransfer
    {
        $productForBundleTransfer = (new ProductForBundleTransfer())
            ->setQuantity(1)
            ->setIdProductConcrete($this->createProduct($sku, $isProductActive)->getIdProductConcrete());
        $productBundleTransfer = (new ProductBundleTransfer())
            ->setIsNeverOutOfStock(true)
            ->addBundledProduct($productForBundleTransfer);

        $productConcreteTransfer = $this->createProduct('sku2', $isProductActive);
        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        $this->getFacade()->saveBundledProducts($productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param string $sku
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProduct(
        string $sku,
        bool $isActive = false
    ): ProductConcreteTransfer {
        $productConcreteTransfer = $this->haveProduct([
            ProductConcreteTransfer::SKU => $sku,
            ProductConcreteTransfer::IS_ACTIVE => $isActive,
        ]);

        $storeTransfer = $this->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->haveProductInStockForStore($storeTransfer, [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::QUANTITY => 10,
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => true,
        ]);

        return $productConcreteTransfer;
    }
}
