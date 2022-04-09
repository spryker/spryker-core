<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesPaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Propel\Runtime\Collection\ObjectCollection;

class SalesPaymentMapper
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $salesPaymentTransfer
     * @param \Orm\Zed\Payment\Persistence\SpySalesPayment $spySalesPayment
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    public function mapSalesPaymentTransferToSalesPaymentEntity(
        SalesPaymentTransfer $salesPaymentTransfer,
        SpySalesPayment $spySalesPayment
    ): SpySalesPayment {
        $spySalesPayment->fromArray($salesPaymentTransfer->modifiedToArray());

        return $spySalesPayment;
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpySalesPayment $spySalesPayment
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $salesPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentTransfer
     */
    public function mapSalesPaymentEntityToSalesPaymentTransfer(
        SpySalesPayment $spySalesPayment,
        SalesPaymentTransfer $salesPaymentTransfer
    ): SalesPaymentTransfer {
        $salesPaymentTransfer->fromArray($spySalesPayment->toArray(), true);
        $salesPaymentTransfer->setPaymentProvider($spySalesPayment->getSalesPaymentMethodType()->getPaymentProvider());
        $salesPaymentTransfer->setPaymentMethod($spySalesPayment->getSalesPaymentMethodType()->getPaymentMethod());

        return $salesPaymentTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Payment\Persistence\SpySalesPayment> $spySalesPaymentCollection
     *
     * @return array<\Generated\Shared\Transfer\SalesPaymentTransfer>
     */
    public function mapSalesPaymentEntityCollectionToSalesPaymentTransferArray(
        ObjectCollection $spySalesPaymentCollection
    ): array {
        $salesPaymentTransfers = [];

        /** @var \Orm\Zed\Payment\Persistence\SpySalesPayment $spySalesPayment */
        foreach ($spySalesPaymentCollection as $spySalesPayment) {
            $salesPaymentTransfers[] = $this->mapSalesPaymentEntityToSalesPaymentTransfer(
                $spySalesPayment,
                new SalesPaymentTransfer(),
            );
        }

        return $salesPaymentTransfers;
    }
}
