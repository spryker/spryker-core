<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleBusinessFactory getFactory()
 */
interface ProductBundleFacadeInterface
{
    /**
     *
     * Specification:
     *
     * - Takes all items to be added to cart and checks if any is bundle item
     * - If bundle item then it is removed, and added to QuoteTransfer::bundleItems, the identifier assigned
     * - Finds all bundled items from that bundle and puts into add to cart operation, assign bundle identifier they belong to.
     * - The price amount is assigned, proportionaly split through items quantity = 1
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleItems(CartChangeTransfer $cartChangeTransfer);

    /**
     *
     * Specification:
     *
     * - It will add images to product bundle
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleItemsWithImages(CartChangeTransfer $cartChangeTransfer);

    /**
     *
     * Specification:
     *
     * - The group key is build to uniquely identify bundled items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleCartItemGroupKey(CartChangeTransfer $cartChangeTransfer);

    /**
     *
     * Specification:
     *
     * - Updates QuoteTransfer::bundleItems to be in sync with current existing bundled items in cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function postSaveCartUpdateBundles(QuoteTransfer $quoteTransfer);

    /**
     *
     * Specification:
     *
     * - Checks if items which being added to cart is available, for bundle it checks bundled items.
     * - Even if same item added separatelly from bundle availability is checked together.
     * - Sets error message if not available
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function preCheckCartAvailability(CartChangeTransfer $cartChangeTransfer);

    /**
     *
     * Specification:
     *
     * - Checks if items which being added to checkout is available, for bundle it checks bundled items.
     * - Even if same item added separatelly from bundle availability is checked together.
     * - Sets error message if not available
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function preCheckCheckoutAvailability(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * * Specification:
     *
     *  - Calculates QuoteTransfer::bundleItems prices
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculateBundlePrice(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *
     * - Gets all items which belong to bundle
     * - Updates bundle products with new availability, given sku belong
     * - Touch abstract availability for bundle product
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return void
     */
    public function updateAffectedBundlesAvailability($concreteSku);

    /**
     * Specification:
     *
     * - Gets all items which belong to bundle
     * - Updates bundle products with new stock, given sku belong
     * - Touch abstract stock for bundle product
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return void
     */
    public function updateAffectedBundlesStock($concreteSku): void;

    /**
     *
     * Specification:
     *
     *  - Calculated bundle availability based on bundled items
     *  - Persists availability
     *  - Touches availability abstract collector for bundle
     *
     * @api
     *
     * @param string $productBundleSku
     *
     * @return void
     */
    public function updateBundleAvailability($productBundleSku);

    /**
     * Specification:
     * - Persists bundled product to sales database tables, from QuoteTransfer
     *
     * @api
     *
     * @deprecated Use saveOrderBundleItems() instead
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSalesOrderBundleItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Persists bundled product to sales database tables, from QuoteTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderBundleItems(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

    /**
     *
     * Specification:
     *
     * - Persists bundled products within ProductConcrete
     * - Updates product bundle available stock
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function saveBundledProducts(ProductConcreteTransfer $productConcreteTransfer);

    /**
     *
     * Specification:
     *
     * - Finds all bundled products to given concrete product
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function findBundledProductsByIdProductConcrete($idProductConcrete);

    /**
     *
     * Specification:
     *
     * - Assigns bundled products to ProductConcreteTransfer::productBundle
     * - Returns modified ProductConcreteTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function assignBundledProductsToProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     *  - Hydrates OrderTransfer with product bundle data
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateSalesOrderProductBundles(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductBundleIds(OrderTransfer $orderTransfer);

    /**
     * Specification:
     *  - Filter bundle items after cart item reload operation is called.
     *  - Bundled items are removed from cart
     *  - Bundle item are added as new add so new prices can be assigned.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterBundleItemsOnCartReload(QuoteTransfer $quoteTransfer);
}
