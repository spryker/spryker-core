<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\RestrictedItemsFilter;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductList\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface;
use Spryker\Zed\ProductList\Dependency\Facade\ProductListToMessengerFacadeInterface;
use Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface;

class RestrictedItemsFilter implements RestrictedItemsFilterInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED = 'product-cart.info.restricted-product.removed';

    /**
     * @var \Spryker\Zed\ProductList\Dependency\Facade\ProductListToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface
     */
    protected $productListRestrictionValidator;

    /**
     * @var \Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductList\Dependency\Facade\ProductListToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\ProductList\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface $productListRestrictionValidator
     * @param \Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductListToMessengerFacadeInterface $messengerFacade,
        ProductListRestrictionValidatorInterface $productListRestrictionValidator,
        ProductListToProductFacadeInterface $productFacade
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
     * @param int[] $customerBlacklistIds
     * @param int[] $customerWhitelistIds
     *
     * @return void
     */
    protected function removeRestrictedItemsFromQuote(
        QuoteTransfer $quoteTransfer,
        $customerBlacklistIds,
        $customerWhitelistIds
    ): void {
        if (!$customerBlacklistIds && !$customerWhitelistIds) {
            return;
        }
        foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
            $idProductConcrete = $this->productFacade->findProductConcreteIdBySku($itemTransfer->getSku());
            $isProductConcreteRestricted = $this->productListRestrictionValidator
                ->isProductConcreteRestricted($idProductConcrete, $customerWhitelistIds, $customerBlacklistIds);
            if ($isProductConcreteRestricted) {
                $quoteTransfer->getItems()->offsetUnset($key);
                $this->addFilterMessage($itemTransfer->getSku());
                continue;
            }

            $isProductAbstractRestricted = $this->productListRestrictionValidator->isProductAbstractRestricted(
                $itemTransfer->getIdProductAbstract(),
                $customerWhitelistIds,
                $customerBlacklistIds
            );
            if ($isProductAbstractRestricted) {
                $quoteTransfer->getItems()->offsetUnset($key);
                $this->addFilterMessage($itemTransfer->getSku());
                continue;
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
