<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartProductListConnector\Business\RestrictedItemsFilter;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartProductListConnector\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToMessengerFacadeInterface;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductFacadeInterface;

class RestrictedItemsFilter implements RestrictedItemsFilterInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED = 'product-cart.info.restricted-product.removed';

    /**
     * @var \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\CartProductListConnector\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface
     */
    protected $productListRestrictionValidator;

    /**
     * @var \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\CartProductListConnector\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface $productListRestrictionValidator
     * @param \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductFacadeInterface $productFacade
     */
    public function __construct(
        CartProductListConnectorToMessengerFacadeInterface $messengerFacade,
        ProductListRestrictionValidatorInterface $productListRestrictionValidator,
        CartProductListConnectorToProductFacadeInterface $productFacade
    ) {
        $this->messengerFacade = $messengerFacade;
        $this->productListRestrictionValidator = $productListRestrictionValidator;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterRestrictedItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getCustomer() && $quoteTransfer->getCustomer()->getCustomerProductListCollection()) {
            $customerProductListCollectionTransfer = $quoteTransfer->getCustomer()->getCustomerProductListCollection();
            $customerWhitelistIds = $customerProductListCollectionTransfer->getWhitelistIds() ?? [];
            $customerBlacklistIds = $customerProductListCollectionTransfer->getBlacklistIds() ?? [];
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
        if (!$customerBlacklistIds && !$customerWhitelistIds) {
            return;
        }
        foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
            $idProductConcrete = $this->productFacade->findProductConcreteIdBySku($itemTransfer->getSku());
            if ($this->productListRestrictionValidator->isProductConcreteRestricted($idProductConcrete, $customerWhitelistIds, $customerBlacklistIds)
                || $this->productListRestrictionValidator->isProductAbstractRestricted($itemTransfer->getIdProductAbstract(), $customerWhitelistIds, $customerBlacklistIds)
            ) {
                $quoteTransfer->getItems()->offsetUnset($key);
                $this->addFilterMessage($itemTransfer->getSku());
            }
        }
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function addFilterMessage(string $sku): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED);
        $messageTransfer->setParameters([
            static::MESSAGE_PARAM_SKU => $sku,
        ]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }
}
