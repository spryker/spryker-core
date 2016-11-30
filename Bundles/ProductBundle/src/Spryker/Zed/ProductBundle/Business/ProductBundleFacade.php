<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleBusinessFactory getFactory()
 */
class ProductBundleFacade extends AbstractFacade
{

    /**
     * @param \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer
     */
    public function addProductBundle(ProductBundleTransfer $productBundleTransfer)
    {
        return $this->getFactory()
            ->createProductBundleWriter()
            ->createProductBundle($productBundleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createProductBundleCartExpander()
            ->expandBundleItems($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleCartItemGroupKey(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createProductBundleCartItemGroupKeyExpander()
            ->expandExpandBundleItemGroupKey($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function postSaveCartUpdateBundles(QuoteTransfer $quoteTransfer)
    {
         return $this->getFactory()
             ->createProductBundlePostSaveUpdate()
             ->updateBundles($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function preCheckCartAvailability(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createProductBundleCartPreCheck()
            ->checkCartAvailability($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculateBundlePrice(QuoteTransfer $quoteTransfer)
    {
         return $this->getFactory()
             ->createProductBundlePriceCalculator()
             ->calculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregateBundlePrice(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createProductBundlePriceCalculator()
            ->aggregate($orderTransfer);
    }

    /**
     * @param string $sku
     */
    public function updateAvailability($sku)
    {

    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSalesOrderBundleItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
         $this->getFactory()
            ->createProductBundleSalesOrderSaver()
            ->saveSaleOrderBundleItems($quoteTransfer, $checkoutResponse);
    }
}
