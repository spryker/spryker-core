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
use Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface;

class ProductListRestrictionValidator implements ProductListRestrictionValidatorInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED = 'product-cart.info.restricted-product.removed';

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface
     */
    protected $productListReader;

    /**
     * @param \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface $productListReader
     */
    public function __construct(
        ProductListReaderInterface $productListReader
    ) {
        $this->productListReader = $productListReader;
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

        $customerWhitelistIds = $customerProductListCollectionTransfer->getWhitelistIds() ?: [];
        $customerBlacklistIds = $customerProductListCollectionTransfer->getBlacklistIds() ?: [];
        $cartChangeSkus = array_map(function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getSku();
        }, $cartChangeTransfer->getItems()->getArrayCopy());

        $restrictedProductConcreteSkus = $this->filterRestrictedProductConcreteSkus($cartChangeSkus, $customerWhitelistIds, $customerBlacklistIds);

        if (!$restrictedProductConcreteSkus) {
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
     * @param string[] $productConcreteSkus
     * @param int[] $customerWhitelistIds
     * @param int[] $customerBlacklistIds
     *
     * @return string[]
     */
    public function filterRestrictedProductConcreteSkus(array $productConcreteSkus, array $customerWhitelistIds, array $customerBlacklistIds): array
    {
        if (!$productConcreteSkus) {
            return [];
        }

        $productConcreteSkusInWhitelist = $productConcreteSkus;
        $productConcreteSkusInBlacklist = [];

        if ($customerWhitelistIds) {
            $productConcreteSkusInWhitelist = $this->productListReader
                ->getConcreteProductSkusInWhitelists($productConcreteSkus, $customerWhitelistIds);
        }

        if ($customerBlacklistIds) {
            $productConcreteSkusInBlacklist = $this->productListReader
                ->getConcreteProductSkusInBlacklists($productConcreteSkus, $customerBlacklistIds);
        }

        if (!$productConcreteSkusInWhitelist && !$productConcreteSkusInBlacklist) {
            return [];
        }

        return array_unique(array_merge(
            $productConcreteSkusInBlacklist,
            array_diff($productConcreteSkus, $productConcreteSkusInWhitelist)
        ));
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
                ->setParameters(['%sku%' => $sku])
        );
    }
}
