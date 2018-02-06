<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface;

class ProductCustomerPermissionCheckoutPreCondition implements ProductCustomerPermissionCheckoutPreConditionInterface
{
    const MESSAGE_NO_PERMISSION = 'checkout.step.error.no-product-permission';

    /**
     * @var \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface $queryContainer
     */
    public function __construct(
        ProductCustomerPermissionQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkPreCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $isPassed = $this->customerHasPermissions($quoteTransfer);

        if (!$isPassed) {
            $checkoutResponseTransfer->addError($this->createCheckoutErrorTransfer($quoteTransfer));
        }

        return $isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(QuoteTransfer $quoteTransfer)
    {
        $deniedProductNames = $this->getDeniedProductNames($quoteTransfer);

        return (new CheckoutErrorTransfer())
            ->setMessage(static::MESSAGE_NO_PERMISSION . ': ' . implode(', ', $deniedProductNames));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function customerHasPermissions(QuoteTransfer $quoteTransfer)
    {
        $idProductAbstracts = $this->getIdProductAbstractCollection($quoteTransfer);
        $idCustomer = $quoteTransfer->getCustomer()->getIdCustomer();

        $permissionCount = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts(
                $idCustomer,
                $idProductAbstracts
            )->count();

        return $permissionCount === count($idProductAbstracts);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    protected function getIdAllowedProductCollection(QuoteTransfer $quoteTransfer)
    {
        $idProductAbstracts = $this->getIdProductAbstractCollection($quoteTransfer);
        $idCustomer = $quoteTransfer->getCustomer()->getIdCustomer();

        $allowedProducts = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts(
                $idCustomer,
                $idProductAbstracts
            )->find();

        $idAllowedProducts = [];
        foreach ($allowedProducts as $allowedProduct) {
            $idAllowedProducts[] = $allowedProduct->getFkProductAbstract();
        }

        return $idAllowedProducts;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function getDeniedProductNames(QuoteTransfer $quoteTransfer)
    {
        $allowedProductIds = $this->getIdAllowedProductCollection($quoteTransfer);
        $deniedProductNames = [];

        foreach ($quoteTransfer->getItems() as $quoteItem) {
            if (!in_array($quoteItem->getIdProductAbstract(), $allowedProductIds)) {
                $deniedProductNames[] = $quoteItem->getName();
            }
        }

        return $deniedProductNames;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    protected function getIdProductAbstractCollection(QuoteTransfer $quoteTransfer)
    {
        $idProductAbstracts = [];
        foreach ($quoteTransfer->getItems() as $cartItem) {
            $idProductAbstract = $cartItem->getIdProductAbstract();
            if (isset($idProductAbstracts[$idProductAbstract])) {
                continue;
            }
            $idProductAbstracts[$idProductAbstract] = $idProductAbstract;
        }

        return $idProductAbstracts;
    }
}
