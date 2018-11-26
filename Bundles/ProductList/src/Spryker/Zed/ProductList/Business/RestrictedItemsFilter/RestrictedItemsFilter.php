<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\RestrictedItemsFilter;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductList\Business\ProductListRestrictionFilter\ProductListRestrictionFilterInterface;
use Spryker\Zed\ProductList\Dependency\Facade\ProductListToMessengerFacadeInterface;

class RestrictedItemsFilter implements RestrictedItemsFilterInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED = 'product-cart.info.restricted-product.removed';

    /**
     * @var \Spryker\Zed\ProductList\Dependency\Facade\ProductListToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListRestrictionFilter\ProductListRestrictionFilterInterface
     */
    protected $productListRestrictionFilter;

    /**
     * @param \Spryker\Zed\ProductList\Dependency\Facade\ProductListToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\ProductList\Business\ProductListRestrictionFilter\ProductListRestrictionFilterInterface $productListRestrictionFilter
     */
    public function __construct(
        ProductListToMessengerFacadeInterface $messengerFacade,
        ProductListRestrictionFilterInterface $productListRestrictionFilter
    ) {
        $this->messengerFacade = $messengerFacade;
        $this->productListRestrictionFilter = $productListRestrictionFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterRestrictedItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $customerTransfer = $quoteTransfer->getCustomer();
        if ($customerTransfer) {
            $customerProductListCollectionTransfer = $customerTransfer->getCustomerProductListCollection();
            if ($customerProductListCollectionTransfer) {
                $customerWhitelistIds = $customerProductListCollectionTransfer->getWhitelistIds() ?: [];
                $customerBlacklistIds = $customerProductListCollectionTransfer->getBlacklistIds() ?: [];
                $this->removeRestrictedItemsFromQuote($quoteTransfer, $customerBlacklistIds, $customerWhitelistIds);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int[] $blacklistIds
     * @param int[] $whitelistIds
     *
     * @return void
     */
    protected function removeRestrictedItemsFromQuote(
        QuoteTransfer $quoteTransfer,
        array $blacklistIds,
        array $whitelistIds
    ): void {
        if (empty($blacklistIds) && empty($whitelistIds)) {
            return;
        }

        $quoteSkus = array_map(function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getSku();
        }, $quoteTransfer->getItems()->getArrayCopy());

        $restrictedProductConcreteSkus = $this->productListRestrictionFilter->filterRestrictedProductConcreteSkus($quoteSkus, $blacklistIds, $whitelistIds);

        if (empty($restrictedProductConcreteSkus)) {
            return;
        }

        $allowedItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (in_array($itemTransfer->getSku(), $restrictedProductConcreteSkus)) {
                $this->addFilterMessage($itemTransfer->getSku());
                continue;
            }

            $allowedItems[] = $itemTransfer;
        }

        $quoteTransfer->getItems()->exchangeArray($allowedItems);
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function addFilterMessage(string $sku): void
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue(static::MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED)
            ->setParameters([
                static::MESSAGE_PARAM_SKU => $sku,
            ]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }
}
