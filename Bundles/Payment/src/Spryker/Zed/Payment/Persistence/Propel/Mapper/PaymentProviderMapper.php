<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentProvider;
use Propel\Runtime\Collection\ObjectCollection;

class PaymentProviderMapper
{
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
            $paymentProviderTransfer = $this->mapPaymentProviderEntityToPaymentProviderTransfer(
                $paymentProviderEntity,
                new PaymentProviderTransfer()
            );
            $paymentProviderTransfer = $this->addPaymentMethodsToPaymentProvider(
                $paymentProviderEntity,
                $paymentProviderTransfer
            );
            $paymentProviderCollectionTransfer->addPaymentProvider($paymentProviderTransfer);
        }

        return $paymentProviderCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentProvider $paymentProviderEntity
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    protected function addPaymentMethodsToPaymentProvider(
        SpyPaymentProvider $paymentProviderEntity,
        PaymentProviderTransfer $paymentProviderTransfer
    ): PaymentProviderTransfer {
        foreach ($paymentProviderEntity->getSpyPaymentMethods() as $paymentMethodEntity) {
            $paymentMethodTransfer = (new PaymentMethodTransfer())
                ->fromArray($paymentMethodEntity->toArray(), true)
                ->setMethodName($paymentMethodEntity->getPaymentMethodKey());
            $paymentProviderTransfer->addPaymentMethod($paymentMethodTransfer);
        }

        return $paymentProviderTransfer;
    }
}
