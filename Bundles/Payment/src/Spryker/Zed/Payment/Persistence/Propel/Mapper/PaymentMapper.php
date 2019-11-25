<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentProvider;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType;
use Propel\Runtime\Collection\ObjectCollection;

class PaymentMapper
{
    /**
     * @param \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType $productPackagingUnitEntity
     * @param \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer
     */
    public function mapSalesPaymentMethodTypeTransfer(
        SpySalesPaymentMethodType $productPackagingUnitEntity,
        SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
    ): SalesPaymentMethodTypeTransfer {
        $salesPaymentMethodTypeTransfer->fromArray($productPackagingUnitEntity->toArray(), true);
        $paymentProviderTransfer = (new PaymentProviderTransfer())
            ->setName($productPackagingUnitEntity->getPaymentProvider());
        $salesPaymentMethodTypeTransfer->setPaymentProvider($paymentProviderTransfer);

        $paymentMethodTransfer = (new PaymentMethodTransfer())
            ->setMethodName($productPackagingUnitEntity->getPaymentMethod());
        $salesPaymentMethodTypeTransfer->setPaymentMethod($paymentMethodTransfer);

        return $salesPaymentMethodTypeTransfer;
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentProvider $paymentProviderEntity
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    public function mapPaymentProviderEntityToPaymentProviderTransfer(
        SpyPaymentProvider $paymentProviderEntity,
        PaymentProviderTransfer $paymentProviderTransfer
    ): PaymentProviderTransfer {
        return $paymentProviderTransfer->fromArray($paymentProviderEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Payment\Persistence\SpyPaymentProvider[] $paymentProviderEntityCollection
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionTransfer $paymentProviderCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    public function mapPaymentProviderEntityCollectionToPaymentProviderCollectionTransfer(
        ObjectCollection $paymentProviderEntityCollection,
        PaymentProviderCollectionTransfer $paymentProviderCollectionTransfer
    ): PaymentProviderCollectionTransfer {
        foreach ($paymentProviderEntityCollection as $paymentProviderEntity) {
            $paymentProviderCollectionTransfer->addPaymentProvider(
                $this->mapPaymentProviderEntityToPaymentProviderTransfer(
                    $paymentProviderEntity,
                    new PaymentProviderTransfer()
                )
            );
        }

        return $paymentProviderCollectionTransfer;
    }
}
