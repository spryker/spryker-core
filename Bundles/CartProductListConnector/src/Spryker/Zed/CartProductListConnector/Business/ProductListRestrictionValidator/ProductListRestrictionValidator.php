<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartProductListConnector\Business\ProductListRestrictionValidator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductFacadeInterface;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductListFacadeInterface;

class ProductListRestrictionValidator implements ProductListRestrictionValidatorInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED = 'product-cart.info.restricted-product.removed';

    /**
     * @var \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @var \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductListFacadeInterface $productListFacade
     */
    public function __construct(
        CartProductListConnectorToProductFacadeInterface $productFacade,
        CartProductListConnectorToProductListFacadeInterface $productListFacade
    ) {
        $this->productListFacade = $productListFacade;
        $this->productFacade = $productFacade;
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
        if (!$customerTransfer || !$customerTransfer->getCustomerProductListCollection()) {
            return $responseTransfer;
        }

        $customerProductListCollectionTransfer = $customerTransfer->getCustomerProductListCollection();
        $customerWhitelistIds = $customerProductListCollectionTransfer->getWhitelistIds() ?? [];
        $customerBlacklistIds = $customerProductListCollectionTransfer->getBlacklistIds() ?? [];

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
        $idProductConcrete = $this->productFacade->findProductConcreteIdBySku($item->getSku());
        if ($this->isProductAbstractRestricted($idProductAbstract, $customerWhitelistIds, $customerBlacklistIds)
            || $this->isProductConcreteRestricted($idProductConcrete, $customerWhitelistIds, $customerBlacklistIds)
        ) {
            $this->addViolation(static::MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED, $item->getSku(), $responseTransfer);
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

        $productAbstractBlacklistIds = $this->productListFacade->getProductAbstractBlacklistIdsByIdProductAbstract($idProductAbstract);
        $productAbstractWhitelistIds = $this->productListFacade->getProductAbstractWhitelistIdsByIdProductAbstract($idProductAbstract);

        $isProductInBlacklist = count(array_intersect($productAbstractBlacklistIds, $customerBlacklistIds));
        $isProductInWhitelist = count(array_intersect($productAbstractWhitelistIds, $customerWhitelistIds));

        return $isProductInBlacklist || (count($productAbstractWhitelistIds) && !$isProductInWhitelist);
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

        $productAbstractBlacklistIds = $this->productListFacade->getProductAbstractBlacklistIdsByIdProductConcrete($idProductConcrete);
        $productAbstractWhitelistIds = $this->productListFacade->getProductAbstractWhitelistIdsByIdProductConcrete($idProductConcrete);

        $isProductInBlacklist = count(array_intersect($productAbstractBlacklistIds, $customerBlacklistIds));
        $isProductInWhitelist = count(array_intersect($productAbstractWhitelistIds, $customerWhitelistIds));

        return $isProductInBlacklist || (count($productAbstractWhitelistIds) && !$isProductInWhitelist);
    }

    /**
     * @param string $message
     * @param string $sku
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function addViolation(string $message, string $sku, CartPreCheckResponseTransfer $responseTransfer): void
    {
        $responseTransfer->setIsSuccess(false);
        $responseTransfer->addMessage(
            (new MessageTransfer())
                ->setValue($message)
                ->setParameters(['%sku%' => $sku])
        );
    }
}
