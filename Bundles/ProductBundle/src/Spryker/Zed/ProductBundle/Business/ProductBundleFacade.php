<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface getRepository()
 */
class ProductBundleFacade extends AbstractFacade implements ProductBundleFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleItemsWithImages(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createProductBundleImageCartExpander()
            ->expandBundleItems($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function preCheckBundledProductPrices(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createProductBundleCartPricePreCheck()
            ->checkCartPrices($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function preCheckCartActive(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createProductBundleCartActiveCheck()
            ->checkActiveItems($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function preCheckCheckoutAvailability(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
         return $this->getFactory()
            ->createProductBundleCheckoutPreCheck()
            ->checkCheckoutAvailability($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return void
     */
    public function updateAffectedBundlesAvailability($concreteSku)
    {
        $this->getFactory()
            ->createProductBundleAvailabilityHandler()
            ->updateAffectedBundlesAvailability($concreteSku);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return void
     */
    public function updateAffectedBundlesStock($concreteSku): void
    {
        $this->getFactory()
            ->createProductBundleStockHandler()
            ->updateAffectedBundlesStock($concreteSku);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $productBundleSku
     *
     * @return void
     */
    public function updateBundleAvailability($productBundleSku)
    {
        $this->getFactory()
            ->createProductBundleAvailabilityHandler()
            ->updateBundleAvailability($productBundleSku);
    }

    /**
     * {@inheritdoc}
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
    public function saveSalesOrderBundleItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
         $this->getFactory()
            ->createProductBundleSalesOrderSaver()
            ->saveSaleOrderBundleItems($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderBundleItems(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->getFactory()
            ->createProductBundleOrderSaver()
            ->saveOrderBundleItems($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function saveBundledProducts(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductBundleWriter()
            ->saveBundledProducts($productConcreteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function findBundledProductsByIdProductConcrete($idProductConcrete)
    {
        return $this->getFactory()
            ->createProductBundleReader()
            ->findBundledProductsByIdProductConcrete($idProductConcrete);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function getProductBundleCollectionByCriteriaFilter(ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer): ProductBundleCollectionTransfer
    {
        return $this->getRepository()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function assignBundledProductsToProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductBundleReader()
            ->assignBundledProductsToProductConcrete($productConcreteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateSalesOrderProductBundles(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createProductBundlesSalesOrderHydrate()
            ->hydrate($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductBundleIds(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createProductBundlesIdHydrator()
            ->hydrate($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterBundleItemsOnCartReload(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createProductBundlePreReloadUpdater()
            ->preReloadItems($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function replaceItemsWithBundleItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()
            ->createChangeRequestExpander()
            ->expand($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function findItemInQuote(QuoteTransfer $quoteTransfer, $sku, $groupKey): ?ItemTransfer
    {
        return $this->getFactory()
            ->createQuoteItemFinder()
            ->findItem($quoteTransfer, $sku, $groupKey);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function checkBundleItemsPriceChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        $this->getFactory()
            ->createProductBundleCartChangeObserver()
            ->checkBundleItemsChanges($resultQuoteTransfer, $sourceQuoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function findBundleItemsInQuote(QuoteTransfer $quoteTransfer, $sku, $groupKey): array
    {
        return $this->getFactory()
            ->createQuoteBundleItemsFinder()
            ->findItems($quoteTransfer, $sku, $groupKey);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function extractQuoteItems(QuoteTransfer $quoteTransfer): ItemCollectionTransfer
    {
        return $this->getFactory()->createQuoteItemsGrouper()->extractQuoteItems($quoteTransfer);
    }
}
