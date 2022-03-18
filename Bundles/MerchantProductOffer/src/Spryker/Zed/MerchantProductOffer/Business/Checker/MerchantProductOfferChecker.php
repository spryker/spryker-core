<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\Checker;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface;

class MerchantProductOfferChecker implements MerchantProductOfferCheckerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_INACTIVE = 'shopping_list.pre.check.product_merchant_inactive';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_NOT_APPROVED = 'shopping_list.pre.check.product_merchant_not_approved';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        MerchantProductOfferToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferToMerchantFacadeInterface $merchantFacade
    ) {
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function check(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer
    {
        $shoppingListPreAddItemCheckResponseTransfer = new ShoppingListPreAddItemCheckResponseTransfer();
        $shoppingListPreAddItemCheckResponseTransfer->setIsSuccess(true);

        $shoppingListPreAddItemCheckResponseTransfer = $this->validateMerchantProductOffer(
            $shoppingListItemTransfer,
            $shoppingListPreAddItemCheckResponseTransfer,
        );

        return $shoppingListPreAddItemCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer $shoppingListPreAddItemCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    protected function validateMerchantProductOffer(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListPreAddItemCheckResponseTransfer $shoppingListPreAddItemCheckResponseTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer {
        $productOfferReference = $shoppingListItemTransfer->getProductOfferReference();

        if (!$productOfferReference) {
            return $shoppingListPreAddItemCheckResponseTransfer;
        }

        $productOfferTransfer = $this->findProductOfferTransferByProductOfferReference($productOfferReference);

        if (!$productOfferTransfer) {
            return $shoppingListPreAddItemCheckResponseTransfer;
        }

        $merchantTransfer = $this->findMerchantTransferByMerchantReference($productOfferTransfer);

        if (!$merchantTransfer) {
            return $shoppingListPreAddItemCheckResponseTransfer;
        }

        if (!$merchantTransfer->getIsActive()) {
            $shoppingListPreAddItemCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_INACTIVE),
                );
        }

        if ($merchantTransfer->getStatus() !== static::MERCHANT_STATUS_APPROVED) {
            $shoppingListPreAddItemCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_NOT_APPROVED),
                );
        }

        return $shoppingListPreAddItemCheckResponseTransfer;
    }

    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    protected function findProductOfferTransferByProductOfferReference(
        string $productOfferReference
    ): ?ProductOfferTransfer {
        $productOfferCriteriaTransfer = new ProductOfferCriteriaTransfer();
        $productOfferCriteriaTransfer->setProductOfferReference($productOfferReference);

        return $this->productOfferFacade->findOne($productOfferCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    protected function findMerchantTransferByMerchantReference(
        ProductOfferTransfer $productOfferTransfer
    ): ?MerchantTransfer {
        $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
        $merchantCriteriaTransfer->setMerchantReference($productOfferTransfer->getMerchantReference());

        return $this->merchantFacade->findOne($merchantCriteriaTransfer);
    }
}
