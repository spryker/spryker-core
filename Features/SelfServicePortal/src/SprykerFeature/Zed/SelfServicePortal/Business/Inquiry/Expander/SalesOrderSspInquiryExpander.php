<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander;

use Generated\Shared\Transfer\OrderConditionsTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SalesOrderSspInquiryExpander implements SspInquiryExpanderInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
        protected SalesFacadeInterface $salesFacade
    ) {
    }

    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
         $sspInquiryIds = array_map(fn ($sspInquiryTransfer) => $sspInquiryTransfer->getIdSspInquiry(), $sspInquiryCollectionTransfer->getSspInquiries()->getArrayCopy());

        $orderSspInquiryCollectionTransfer = $this->selfServicePortalRepository->getSspInquiryOrderCollection(
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

    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return $sspInquiryCriteriaTransfer->getInclude() && $sspInquiryCriteriaTransfer->getInclude()->getWithOrder();
    }
}
