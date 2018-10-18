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
use Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface;

class ProductListRestrictionValidator implements ProductListRestrictionValidatorInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED = 'product-cart.info.restricted-product.removed';

    /**
     * @var \Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface
     */
    protected $productListReader;

    /**
     * @param \Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface $productListReader
     */
    public function __construct(
        ProductListToProductFacadeInterface $productFacade,
        ProductListReaderInterface $productListReader
    ) {
        $this->productFacade = $productFacade;
        $this->productListReader = $productListReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddition(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $responseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        $customerTransfer = $cartChangeTransfer->getQuote()->getCustomer();
        if (!$customerTransfer) {
            return $responseTransfer;
        }

        $customerProductListCollectionTransfer = $customerTransfer->getCustomerProductListCollection();
        if (!$customerProductListCollectionTransfer) {
            return $responseTransfer;
        }

        $customerWhitelistIds = $customerProductListCollectionTransfer->getWhitelistIds() ?: [];
        $customerBlacklistIds = $customerProductListCollectionTransfer->getBlacklistIds() ?: [];

        foreach ($cartChangeTransfer->getItems() as $item) {
            $this->validateItem(
                $item,
                $responseTransfer,
                $customerWhitelistIds,
                $customerBlacklistIds
            );
        }

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     * @param int[] $customerWhitelistIds
     * @param int[] $customerBlacklistIds
     *
     * @return void
     */
    protected function validateItem(
        ItemTransfer $item,
        CartPreCheckResponseTransfer $responseTransfer,
        array $customerWhitelistIds,
        array $customerBlacklistIds
    ): void {
        $idProductAbstract = $this->productFacade->getProductAbstractIdByConcreteSku($item->getSku());
        if ($this->isProductAbstractRestricted($idProductAbstract, $customerWhitelistIds, $customerBlacklistIds)) {
            $this->addViolation($item->getSku(), $responseTransfer);
            return;
        }

        $idProductConcrete = $this->productFacade->findProductConcreteIdBySku($item->getSku());
        if ($this->isProductConcreteRestricted($idProductConcrete, $customerWhitelistIds, $customerBlacklistIds)) {
            $this->addViolation($item->getSku(), $responseTransfer);
            return;
        }
    }

    /**
     * @param int $idProductAbstract
     * @param int[] $customerWhitelistIds
     * @param int[] $customerBlacklistIds
     *
     * @return bool
     */
    public function isProductAbstractRestricted(
        int $idProductAbstract,
        array $customerWhitelistIds,
        array $customerBlacklistIds
    ): bool {
        if (!$customerBlacklistIds && !$customerWhitelistIds) {
            return false;
        }

        $productAbstractBlacklistIds = $this->productListReader
            ->getProductAbstractBlacklistIdsByIdProductAbstract($idProductAbstract);
        $isProductInBlacklist = !empty(array_intersect($productAbstractBlacklistIds, $customerBlacklistIds));

        $productAbstractWhitelistIds = $this->productListReader
            ->getProductAbstractWhitelistIdsByIdProductAbstract($idProductAbstract);
        $isProductInWhitelist = !empty(array_intersect($productAbstractWhitelistIds, $customerWhitelistIds));

        return $isProductInBlacklist || !(empty($productAbstractWhitelistIds) || $isProductInWhitelist);
    }

    /**
     * @param int $idProductConcrete
     * @param array $customerWhitelistIds
     * @param array $customerBlacklistIds
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProductConcrete, array $customerWhitelistIds, array $customerBlacklistIds): bool
    {
        if (!$customerBlacklistIds && !$customerWhitelistIds) {
            return false;
        }

        $productAbstractBlacklistIds = $this->productListReader
            ->getProductAbstractBlacklistIdsByIdProductConcrete($idProductConcrete);
        $isProductInBlacklist = !empty(array_intersect($productAbstractBlacklistIds, $customerBlacklistIds));

        $productAbstractWhitelistIds = $this->productListReader
            ->getProductAbstractWhitelistIdsByIdProductConcrete($idProductConcrete);
        $isProductInWhitelist = !empty(array_intersect($productAbstractWhitelistIds, $customerWhitelistIds));

        return $isProductInBlacklist || !(empty($productAbstractWhitelistIds) || $isProductInWhitelist);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function addViolation(string $sku, CartPreCheckResponseTransfer $responseTransfer): void
    {
        $responseTransfer->setIsSuccess(false);
        $responseTransfer->addMessage(
            (new MessageTransfer())
                ->setValue(static::MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED)
                ->setParameters(['%sku%' => $sku])
        );
    }
}
