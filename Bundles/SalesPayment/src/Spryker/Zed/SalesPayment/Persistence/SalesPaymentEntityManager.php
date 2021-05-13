<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Persistence;

use Generated\Shared\Transfer\SalesPaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentPersistenceFactory getFactory()
 */
class SalesPaymentEntityManager extends AbstractEntityManager implements SalesPaymentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $salesPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentTransfer
     */
    public function createSalesPayment(SalesPaymentTransfer $salesPaymentTransfer): SalesPaymentTransfer
    {
        /** @var string $paymentProvider */
        $paymentProvider = $salesPaymentTransfer->getPaymentProvider();
        /** @var string $paymentMethod */
        $paymentMethod = $salesPaymentTransfer->getPaymentMethod();

        $idSalesPaymentMethodType = $this->getIdSalesPaymentMethodType(
            $paymentProvider,
            $paymentMethod
        );

        $salesPaymentEntity = $this->getFactory()
            ->createSalesPaymentMapper()
            ->mapSalesPaymentTransferToSalesPaymentEntity($salesPaymentTransfer, (new SpySalesPayment()));
        $salesPaymentEntity->setFkSalesPaymentMethodType($idSalesPaymentMethodType);

        $salesPaymentEntity->save();

        return $this->getFactory()
            ->createSalesPaymentMapper()
            ->mapSalesPaymentEntityToSalesPaymentTransfer($salesPaymentEntity, $salesPaymentTransfer);
    }

    /**
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return int
     */
    protected function getIdSalesPaymentMethodType(string $paymentProvider, string $paymentMethod): int
    {
        $salesPaymentMethodTypeEntity = $this->getFactory()
            ->createSalesPaymentMethodTypeQuery()
            ->filterByPaymentMethod($paymentMethod)
            ->filterByPaymentProvider($paymentProvider)
            ->findOneOrCreate();

        $salesPaymentMethodTypeEntity->save();

        return $salesPaymentMethodTypeEntity->getIdSalesPaymentMethodType();
    }
}
