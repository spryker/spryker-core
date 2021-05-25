<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Writer;

use Generated\Shared\Transfer\PaymentMethodResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface;

class PaymentWriter implements PaymentWriterInterface
{
    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface $entityManager
     */
    public function __construct(PaymentEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderResponseTransfer
     */
    public function createPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): PaymentProviderResponseTransfer
    {
        $paymentProviderTransfer->requirePaymentProviderKey();
        $paymentProviderTransfer->requireName();

        $paymentProviderTransfer = $this->entityManager->createPaymentProvider($paymentProviderTransfer);
        $paymentProviderResponseTransfer = (new PaymentProviderResponseTransfer())
            ->setPaymentProvider($paymentProviderTransfer)
            ->setIsSuccessful((bool)$paymentProviderTransfer->getIdPaymentProvider());

        if ($paymentProviderTransfer->getPaymentMethods()->count() === 0) {
            return $paymentProviderResponseTransfer;
        }

        foreach ($paymentProviderTransfer->getPaymentMethods() as $paymentMethodTransfer) {
            $paymentMethodTransfer->setIdPaymentProvider($paymentProviderTransfer->getIdPaymentProvider());
            $paymentMethodResponseTransfer = $this->createPaymentMethod($paymentMethodTransfer);
            $paymentProviderTransfer->addPaymentMethod($paymentMethodResponseTransfer->getPaymentMethod());
        }

        $paymentProviderResponseTransfer->setPaymentProvider($paymentProviderTransfer);

        return $paymentProviderResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function createPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer
    {
        $paymentMethodTransfer
            ->requireIdPaymentProvider()
            ->requirePaymentMethodKey()
            ->requireName();

        $storeRelationTransfer = $paymentMethodTransfer->getStoreRelation();
        $paymentMethodTransfer = $this->entityManager->createPaymentMethod($paymentMethodTransfer);

        if ($storeRelationTransfer && $storeRelationTransfer->getIdStores()) {
            $this->entityManager->addPaymentMethodStoreRelationsForStores(
                $storeRelationTransfer->getIdStores(),
                $paymentMethodTransfer->getIdPaymentMethod()
            );
        }

        return (new PaymentMethodResponseTransfer())
            ->setPaymentMethod($paymentMethodTransfer)
            ->setIsSuccessful((bool)$paymentMethodTransfer->getIdPaymentMethod());
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function deactivatePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer
    {
        $paymentMethodTransfer->setIsActive(false);

        $paymentMethodTransfer = $this->entityManager->updatePaymentMethod($paymentMethodTransfer);

        return (new PaymentMethodResponseTransfer())
            ->setPaymentMethod($paymentMethodTransfer)
            ->setIsSuccessful($paymentMethodTransfer !== null);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function activatePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer
    {
        $paymentMethodTransfer->setIsActive(true);

        $paymentMethodTransfer = $this->entityManager->updatePaymentMethod($paymentMethodTransfer);

        return (new PaymentMethodResponseTransfer())
            ->setPaymentMethod($paymentMethodTransfer)
            ->setIsSuccessful($paymentMethodTransfer !== null);
    }
}
