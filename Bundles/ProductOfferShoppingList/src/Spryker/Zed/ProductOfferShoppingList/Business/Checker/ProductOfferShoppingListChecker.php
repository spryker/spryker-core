<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShoppingList\Business\Checker;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\ProductOfferShoppingList\Dependency\Facade\ProductOfferShoppingListToProductOfferFacadeInterface;

class ProductOfferShoppingListChecker implements ProductOfferShoppingListCheckerInterface
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    protected const TRANSLATION_PARAMETER_SKU = '%sku%';

    /**
     * @var string
     */
    protected const TRANSLATION_PARAMETER_PRODUCT_OFFER_REFERENCE = '%productOfferReference%';

    /**
     * @var string
     */
    protected const ERROR_SHOPPING_LIST_PRE_CHECK_PRODUCT_OFFER = 'shopping_list.pre.check.product_offer';

    /**
     * @var string
     */
    protected const ERROR_SHOPPING_LIST_PRE_CHECK_PRODUCT_OFFER_IS_ACTIVE = 'shopping_list.pre.check.product_offer.is_active';

    /**
     * @var string
     */
    protected const ERROR_SHOPPING_LIST_PRE_CHECK_PRODUCT_OFFER_APPROVED = 'shopping_list.pre.check.product_offer.approved';

    /**
     * @var \Spryker\Zed\ProductOfferShoppingList\Dependency\Facade\ProductOfferShoppingListToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShoppingList\Dependency\Facade\ProductOfferShoppingListToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(ProductOfferShoppingListToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkProductOfferShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer {
        /** @var string $productOfferReference */
        $productOfferReference = $shoppingListItemTransfer->getProductOfferReference();
        $shoppingListPreAddItemCheckResponseTransfer = (new ShoppingListPreAddItemCheckResponseTransfer());

        if (!$productOfferReference) {
            return $shoppingListPreAddItemCheckResponseTransfer->setIsSuccess(true);
        }

        /** @var string $sku */
        $sku = $shoppingListItemTransfer->getSku();
        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setProductOfferReference($productOfferReference)
            ->setConcreteSku($sku);
        $productOfferTransfer = $this->productOfferFacade->findOne($productOfferCriteriaTransfer);

        if (!$productOfferTransfer) {
            return $shoppingListPreAddItemCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage(
                    $this->createMessage(
                        static::ERROR_SHOPPING_LIST_PRE_CHECK_PRODUCT_OFFER,
                        $sku,
                        $productOfferReference,
                    ),
                );
        }

        if (!$productOfferTransfer->getIsActive()) {
            $shoppingListPreAddItemCheckResponseTransfer->addMessage(
                $this->createMessage(
                    static::ERROR_SHOPPING_LIST_PRE_CHECK_PRODUCT_OFFER_IS_ACTIVE,
                    $sku,
                    $productOfferReference,
                ),
            );
        }

        if ($productOfferTransfer->getApprovalStatus() !== static::STATUS_APPROVED) {
            $shoppingListPreAddItemCheckResponseTransfer->addMessage(
                $this->createMessage(
                    static::ERROR_SHOPPING_LIST_PRE_CHECK_PRODUCT_OFFER_APPROVED,
                    $sku,
                    $productOfferReference,
                ),
            );
        }

        $shoppingListPreAddItemCheckResponseTransfer->setIsSuccess(
            $shoppingListPreAddItemCheckResponseTransfer->getMessages()->count() === 0,
        );

        return $shoppingListPreAddItemCheckResponseTransfer;
    }

    /**
     * @param string $message
     * @param string $translationSku
     * @param string $translationProductOfferReference
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage(string $message, string $translationSku, string $translationProductOfferReference): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($message)
            ->setParameters([
                static::TRANSLATION_PARAMETER_SKU => $translationSku,
                static::TRANSLATION_PARAMETER_PRODUCT_OFFER_REFERENCE => $translationProductOfferReference,
            ]);
    }
}
