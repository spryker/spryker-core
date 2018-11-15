<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductListRestrictionValidator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductList\Business\ProductListRestrictionFilter\ProductListRestrictionFilterInterface;

class ProductListRestrictionValidator implements ProductListRestrictionValidatorInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED = 'product-cart.info.restricted-product.removed';

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListRestrictionFilter\ProductListRestrictionFilterInterface
     */
    protected $productListRestrictionFilter;

    /**
     * @param \Spryker\Zed\ProductList\Business\ProductListRestrictionFilter\ProductListRestrictionFilterInterface $productListRestrictionFilter
     */
    public function __construct(
        ProductListRestrictionFilterInterface $productListRestrictionFilter
    ) {
        $this->productListRestrictionFilter = $productListRestrictionFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddition(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        $customerTransfer = $cartChangeTransfer->getQuote()->getCustomer();
        if (!$customerTransfer) {
            return $cartPreCheckResponseTransfer;
        }

        $customerProductListCollectionTransfer = $customerTransfer->getCustomerProductListCollection();
        if (!$customerProductListCollectionTransfer) {
            return $cartPreCheckResponseTransfer;
        }

        $customerBlacklistIds = $customerProductListCollectionTransfer->getBlacklistIds() ?: [];
        $customerWhitelistIds = $customerProductListCollectionTransfer->getWhitelistIds() ?: [];
        $cartChangeSkus = array_map(function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getSku();
        }, $cartChangeTransfer->getItems()->getArrayCopy());

        $restrictedProductConcreteSkus = $this->productListRestrictionFilter
            ->filterRestrictedProductConcreteSkus(
                $cartChangeSkus,
                $customerBlacklistIds,
                $customerWhitelistIds
            );

        if (empty($restrictedProductConcreteSkus)) {
            return $cartPreCheckResponseTransfer;
        }

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->validateItem(
                $itemTransfer,
                $cartPreCheckResponseTransfer,
                $restrictedProductConcreteSkus
            );
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     * @param string[] $restrictedProductConcreteSkus
     *
     * @return void
     */
    protected function validateItem(
        ItemTransfer $itemTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer,
        array $restrictedProductConcreteSkus
    ): void {
        if (in_array($itemTransfer->getSku(), $restrictedProductConcreteSkus)) {
            $this->addViolation($itemTransfer->getSku(), $cartPreCheckResponseTransfer);

            return;
        }
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return void
     */
    protected function addViolation(string $sku, CartPreCheckResponseTransfer $cartPreCheckResponseTransfer): void
    {
        $cartPreCheckResponseTransfer->setIsSuccess(false);
        $cartPreCheckResponseTransfer->addMessage(
            (new MessageTransfer())
                ->setValue(static::MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED)
                ->setParameters([static::MESSAGE_PARAM_SKU => $sku])
        );
    }
}
