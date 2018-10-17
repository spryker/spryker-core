<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToGlossaryFacadeInterface;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface;

class ProductCustomerPermissionCheckoutPreCondition implements ProductCustomerPermissionCheckoutPreConditionInterface
{
    protected const MESSAGE_NO_PERMISSION = 'checkout.step.error.no-product-permission';

    /**
     * @var \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        ProductCustomerPermissionQueryContainerInterface $queryContainer,
        ProductCustomerPermissionToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkPreCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $isPassed = $this->hasCustomerPermissions($quoteTransfer);

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
    protected function createCheckoutErrorTransfer(QuoteTransfer $quoteTransfer): CheckoutErrorTransfer
    {
        $message = $this->glossaryFacade->translate(static::MESSAGE_NO_PERMISSION)
            . ': ' . implode(', ', $this->getDeniedProductAbstractNames($quoteTransfer));

        return (new CheckoutErrorTransfer())
            ->setMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasCustomerPermissions(QuoteTransfer $quoteTransfer): bool
    {
        $idProductAbstracts = $this->getIdProductAbstracts($quoteTransfer);
        $idCustomer = $quoteTransfer->getCustomer()->getIdCustomer();

        if ($idCustomer === null) {
            return false;
        }

        $productCustomerPermissionCount = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts(
                $idCustomer,
                $idProductAbstracts
            )->count();

        return $productCustomerPermissionCount === count($idProductAbstracts);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    protected function getAllowedIdProductAbstracts(QuoteTransfer $quoteTransfer): array
    {
        $idProductAbstracts = $this->getIdProductAbstracts($quoteTransfer);
        $idCustomer = $quoteTransfer->getCustomer()->getIdCustomer();

        if ($idCustomer === null) {
            return [];
        }

        $allowedProducts = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts(
                $idCustomer,
                $idProductAbstracts
            )->find();

        $allowedIdProductAbstracts = [];
        foreach ($allowedProducts as $allowedProduct) {
            $allowedIdProductAbstracts[] = $allowedProduct->getFkProductAbstract();
        }

        return $allowedIdProductAbstracts;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function getDeniedProductAbstractNames(QuoteTransfer $quoteTransfer): array
    {
        $allowedIdProductAbstracts = $this->getAllowedIdProductAbstracts($quoteTransfer);
        $deniedProductAbstractNames = [];

        foreach ($quoteTransfer->getItems() as $quoteItem) {
            if (!in_array($quoteItem->getIdProductAbstract(), $allowedIdProductAbstracts, true)) {
                $deniedProductAbstractNames[] = $quoteItem->getName();
            }
        }

        return $deniedProductAbstractNames;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    protected function getIdProductAbstracts(QuoteTransfer $quoteTransfer): array
    {
        $idProductAbstracts = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $idProductAbstract = $itemTransfer->getIdProductAbstract();
            if (isset($idProductAbstracts[$idProductAbstract])) {
                continue;
            }
            $idProductAbstracts[$idProductAbstract] = $idProductAbstract;
        }

        return $idProductAbstracts;
    }
}
