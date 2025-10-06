<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business\OrderProvider;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\OrderConditionsTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CompanySalesConnector\Business\Checker\EditCompanyOrdersPermissionCheckerInterface;
use Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToSalesFacadeInterface;

class EditCompanyOrderCartReorderOrderProvider implements EditCompanyOrderCartReorderOrderProviderInterface
{
    /**
     * @param \Spryker\Zed\CompanySalesConnector\Business\Checker\EditCompanyOrdersPermissionCheckerInterface $editCompanyOrdersPermissionChecker
     * @param \Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        protected EditCompanyOrdersPermissionCheckerInterface $editCompanyOrdersPermissionChecker,
        protected CompanySalesConnectorToSalesFacadeInterface $salesFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrder(CartReorderRequestTransfer $cartReorderRequestTransfer): ?OrderTransfer
    {
        if (!$cartReorderRequestTransfer->getIsAmendment()) {
            return null;
        }

        if (!$this->editCompanyOrdersPermissionChecker->isEditCompanyOrderCartReorderAllowed($cartReorderRequestTransfer)) {
            return null;
        }

        $orderTransfer = $this->findOrderByOrderReference($cartReorderRequestTransfer);

        if (!$this->editCompanyOrdersPermissionChecker->isOrderBelongsToCompany($orderTransfer, $cartReorderRequestTransfer->getCompanyUserTransferOrFail())) {
            return null;
        }

        if (!$orderTransfer->getCustomerReference()) {
            return null;
        }

        $orderListRequestTransfer = (new OrderListRequestTransfer())
            ->setCustomerReference($orderTransfer->getCustomerReferenceOrFail())
            ->addOrderReference($cartReorderRequestTransfer->getOrderReferenceOrFail());

        return $this->salesFacade
            ->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer)
            ->getOrders()
            ->getIterator()
            ->current();
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    protected function findOrderByOrderReference(CartReorderRequestTransfer $cartReorderRequestTransfer): ?OrderTransfer
    {
        $orderCriteriaTransfer = (new OrderCriteriaTransfer())
            ->setOrderConditions((new OrderConditionsTransfer())
                ->addOrderReference($cartReorderRequestTransfer->getOrderReferenceOrFail()));

        $orders = $this->salesFacade->getOrderCollection($orderCriteriaTransfer)->getOrders();

        if ($orders->count() === 0) {
            return null;
        }

        return $orders->getIterator()->current();
    }
}
