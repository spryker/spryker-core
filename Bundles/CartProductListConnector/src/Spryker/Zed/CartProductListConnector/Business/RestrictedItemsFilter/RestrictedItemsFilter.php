<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartProductListConnector\Business\RestrictedItemsFilter;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToMessengerFacadeInterface;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductListFacadeInterface;

class RestrictedItemsFilter implements RestrictedItemsFilterInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_CONCRETE_INACTIVE_PRODUCT_REMOVED = 'product-cart.info.concrete-product-inactive.removed';

    /**
     * @var \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @param \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductListFacadeInterface $productListFacade
     */
    public function __construct(
        CartProductListConnectorToMessengerFacadeInterface $messengerFacade,
        CartProductListConnectorToProductListFacadeInterface $productListFacade
    ) {
        $this->messengerFacade = $messengerFacade;
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterRestrictedItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getCustomer()->getCustomerProductListCollection()) {
            $customerProductListCollectionTransfer = $quoteTransfer->getCustomer()->getCustomerProductListCollection();
            $customerBlacklistIds = $this->getCustomerBlacklistIds($customerProductListCollectionTransfer);
            $customerWhitelistIds = $this->getCustomerWhitelistIds($customerProductListCollectionTransfer);
            $this->removeRestrictedItemsFromQuote($quoteTransfer, $customerBlacklistIds, $customerWhitelistIds);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int[] $customerBlacklistIds
     * @param int[] $customerWhitelistIds
     *
     * @return void
     */
    protected function removeRestrictedItemsFromQuote(QuoteTransfer $quoteTransfer, $customerBlacklistIds, $customerWhitelistIds): void
    {
        if ($customerBlacklistIds || $customerWhitelistIds) {
            foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
                if ($this->isProductAbstractRestricted($itemTransfer->getIdProductAbstract(), $customerWhitelistIds, $customerBlacklistIds)) {
                    $quoteTransfer->getItems()->offsetUnset($key);
                    $this->addFilterMessage($itemTransfer->getSku());
                }
            }
        }
    }

    /**
     * @param int $idProductAbstract
     * @param int[] $customerWhitelistIds
     * @param int[] $customerBlacklistIds
     *
     * @return bool
     */
    protected function isProductAbstractRestricted(
        int $idProductAbstract,
        array $customerWhitelistIds,
        array $customerBlacklistIds
    ): bool {
        $productAbstractBlacklistIds = $this->productListFacade->getProductAbstractBlacklistIdsByIdProductAbstract($idProductAbstract);
        $productAbstractWhitelistIds = $this->productListFacade->getProductAbstractWhitelistIdsByIdProductAbstract($idProductAbstract);
        $isProductInBlacklist = count(array_intersect($productAbstractBlacklistIds, $customerBlacklistIds));
        $isProductInWhitelist = count(array_intersect($productAbstractWhitelistIds, $customerWhitelistIds));

        return !$isProductInBlacklist && $isProductInWhitelist;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerProductListCollectionTransfer $customerProductListCollectionTransfer
     *
     * @return array
     */
    protected function getCustomerBlacklistIds(CustomerProductListCollectionTransfer $customerProductListCollectionTransfer): array
    {
        $customerBlacklistIds = [];

        foreach ($customerProductListCollectionTransfer->getBlacklists() as $productListTransfer) {
            $customerBlacklistIds[] = $productListTransfer->getIdProductList();
        }

        return $customerBlacklistIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerProductListCollectionTransfer $customerProductListCollectionTransfer
     *
     * @return array
     */
    protected function getCustomerWhitelistIds(CustomerProductListCollectionTransfer $customerProductListCollectionTransfer): array
    {
        $customerWhitelistIds = [];

        foreach ($customerProductListCollectionTransfer->getWhitelists() as $productListTransfer) {
            $customerWhitelistIds[] = $productListTransfer->getIdProductList();
        }

        return $customerWhitelistIds;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function addFilterMessage(string $sku): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::MESSAGE_INFO_CONCRETE_INACTIVE_PRODUCT_REMOVED);
        $messageTransfer->setParameters([
            static::MESSAGE_PARAM_SKU => $sku,
        ]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }
}
