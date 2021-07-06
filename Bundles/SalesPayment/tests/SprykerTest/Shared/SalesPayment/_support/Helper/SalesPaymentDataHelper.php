<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SalesPayment\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType;

class SalesPaymentDataHelper extends Module
{
    /**
     * @param iterable|\Generated\Shared\Transfer\SalesPaymentTransfer[] $salesPaymentTransfers
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment[]
     */
    public function haveSalesPaymentEntities(iterable $salesPaymentTransfers = []): array
    {
        $entities = [];

        foreach ($salesPaymentTransfers as $salesPaymentTransfer) {
            $entities[] = $this->haveSalesPaymentEntity($salesPaymentTransfer);
        }

        return $entities;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $salesPaymentTransfer
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    public function haveSalesPaymentEntity(SalesPaymentTransfer $salesPaymentTransfer): SpySalesPayment
    {
        $salesPaymentMethodTypeEntity = $this->haveSalesPaymentMethodType(
            $salesPaymentTransfer->getPaymentProvider(),
            $salesPaymentTransfer->getPaymentMethod()
        );

        $salesPaymentEntity = new SpySalesPayment();
        $salesPaymentEntity->setSalesPaymentMethodType($salesPaymentMethodTypeEntity);
        $salesPaymentEntity->setFkSalesOrder($salesPaymentTransfer->getFkSalesOrder());
        $salesPaymentEntity->setAmount($salesPaymentTransfer->getAmount());
        $salesPaymentEntity->save();

        return $salesPaymentEntity;
    }

    /**
     * @param string $providerName
     * @param string $methodName
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType
     */
    protected function haveSalesPaymentMethodType(string $providerName, string $methodName): SpySalesPaymentMethodType
    {
        $salesPaymentMethodTypeEntity = new SpySalesPaymentMethodType();

        $salesPaymentMethodTypeEntity->setPaymentProvider($providerName);
        $salesPaymentMethodTypeEntity->setPaymentMethod($methodName);
        $salesPaymentMethodTypeEntity->save();

        return $salesPaymentMethodTypeEntity;
    }
}
