<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Expander;

use Generated\Shared\Transfer\OrderConditionsTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface;

class SalesOrderSspInquiryExpander implements SspInquiryExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface $sspInquiryManagementRepository
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     */
    public function __construct(
        protected SspInquiryManagementRepositoryInterface $sspInquiryManagementRepository,
        protected SalesFacadeInterface $salesFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
         $sspInquiryIds = array_map(fn ($sspInquiryTransfer) => $sspInquiryTransfer->getIdSspInquiry(), $sspInquiryCollectionTransfer->getSspInquiries()->getArrayCopy());

        $orderSspInquiryCollectionTransfer = $this->sspInquiryManagementRepository->getSspInquiryOrderCollection(
            (new SspInquiryCriteriaTransfer())->setSspInquiryConditions((new SspInquiryConditionsTransfer())->setSspInquiryIds($sspInquiryIds)),
        );

        $orderCollectionTransfer = $this->salesFacade->getOrderCollection((new OrderCriteriaTransfer())->setOrderConditions(
            (new OrderConditionsTransfer())->setSalesOrderIds(
                array_map(fn ($orderSspInquiryTransfer) => $orderSspInquiryTransfer->getOrder()->getIdSalesOrder(), $orderSspInquiryCollectionTransfer->getSspInquiries()->getArrayCopy()),
            ),
        ));

        $ordersGroupedByOrderId = [];
        foreach ($orderCollectionTransfer->getOrders() as $orderTransfer) {
            $ordersGroupedByOrderId[$orderTransfer->getIdSalesOrder()] = $orderTransfer;
        }

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            if ($sspInquiryTransfer->getOrder()) {
                continue;
            }

            foreach ($orderSspInquiryCollectionTransfer->getSspInquiries() as $orderSspInquiryTransfer) {
                if ($sspInquiryTransfer->getIdSspInquiry() !== $orderSspInquiryTransfer->getIdSspInquiry()) {
                    continue;
                }
                 $sspInquiryTransfer->setOrder($ordersGroupedByOrderId[$orderSspInquiryTransfer->getOrderOrFail()->getIdSalesOrder()]);
            }
        }

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return $sspInquiryCriteriaTransfer->getInclude() && $sspInquiryCriteriaTransfer->getInclude()->getWithOrder();
    }
}
