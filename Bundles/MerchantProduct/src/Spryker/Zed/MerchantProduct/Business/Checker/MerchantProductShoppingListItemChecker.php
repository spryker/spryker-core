<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Checker;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToMerchantFacadeInterface;

class MerchantProductShoppingListItemChecker implements MerchantProductShoppingListItemCheckerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_MERCHANT_INACTIVE = 'shopping_list.pre.check.product_merchant_inactive';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_MERCHANT_NOT_APPROVED = 'shopping_list.pre.check.product_merchant_not_approved';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        MerchantProductToMerchantFacadeInterface $merchantFacade
    ) {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function check(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer
    {
        $shoppingListPreAddItemCheckResponseTransfer = (new ShoppingListPreAddItemCheckResponseTransfer());
        $shoppingListPreAddItemCheckResponseTransfer->setIsSuccess(true);

        if (!$shoppingListItemTransfer->getMerchantReference()) {
            return $shoppingListPreAddItemCheckResponseTransfer;
        }

        $merchantTransfer = $this->findMerchantTransfer($shoppingListItemTransfer);

        if (!$merchantTransfer) {
            return $shoppingListPreAddItemCheckResponseTransfer;
        }

        if (!$merchantTransfer->getIsActive()) {
            $shoppingListPreAddItemCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage(
                    (new MessageTransfer())
                        ->setValue(static::GLOSSARY_KEY_PRODUCT_MERCHANT_INACTIVE),
                );
        }

        if ($merchantTransfer->getStatus() !== static::MERCHANT_STATUS_APPROVED) {
            $shoppingListPreAddItemCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage(
                    (new MessageTransfer())
                        ->setValue(static::GLOSSARY_KEY_PRODUCT_MERCHANT_NOT_APPROVED),
                );
        }

        return $shoppingListPreAddItemCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    protected function findMerchantTransfer(ShoppingListItemTransfer $shoppingListItemTransfer): ?MerchantTransfer
    {
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setMerchantReference($shoppingListItemTransfer->getMerchantReferenceOrFail());

        return $this->merchantFacade->findOne($merchantCriteriaTransfer);
    }
}
