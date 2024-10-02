<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Business\MessageBroker;

use Generated\Shared\Transfer\PaymentCreatedTransfer;
use Generated\Shared\Transfer\PaymentUpdatedTransfer;
use Generated\Shared\Transfer\SalesPaymentDetailTransfer;
use Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailEntityManagerInterface;
use Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailRepositoryInterface;

class PaymentMessageHandler implements PaymentMessageHandlerInterface
{
    /**
     * @var \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailRepositoryInterface
     */
    protected SalesPaymentDetailRepositoryInterface $salesPaymentDetailRepository;

    /**
     * @var \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailEntityManagerInterface
     */
    protected SalesPaymentDetailEntityManagerInterface $salesPaymentDetailEntityManager;

    /**
     * @param \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailRepositoryInterface $salesPaymentDetailRepository
     * @param \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailEntityManagerInterface $salesPaymentDetailEntityManager
     */
    public function __construct(
        SalesPaymentDetailRepositoryInterface $salesPaymentDetailRepository,
        SalesPaymentDetailEntityManagerInterface $salesPaymentDetailEntityManager
    ) {
        $this->salesPaymentDetailRepository = $salesPaymentDetailRepository;
        $this->salesPaymentDetailEntityManager = $salesPaymentDetailEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCreatedTransfer $paymentCreatedTransfer
     *
     * @return void
     */
    public function handlePaymentCreated(PaymentCreatedTransfer $paymentCreatedTransfer): void
    {
        if (($paymentCreatedTransfer->getEntityReference() && $this->salesPaymentDetailRepository->findByEntityReference($paymentCreatedTransfer->getEntityReferenceOrFail())) || $this->salesPaymentDetailRepository->findByPaymentReference($paymentCreatedTransfer->getPaymentReferenceOrFail())) {
            return;
        }

        $salesPaymentDetailTransfer = new SalesPaymentDetailTransfer();
        $salesPaymentDetailTransfer->fromArray($paymentCreatedTransfer->toArray(), true);

        $this->salesPaymentDetailEntityManager->createSalesPaymentDetails($salesPaymentDetailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentUpdatedTransfer $paymentUpdatedTransfer
     *
     * @return void
     */
    public function handlePaymentUpdated(PaymentUpdatedTransfer $paymentUpdatedTransfer): void
    {
        $salesPaymentDetailTransfer = $this->salesPaymentDetailRepository->findByEntityReference($paymentUpdatedTransfer->getEntityReferenceOrFail());

        if (!$salesPaymentDetailTransfer) {
            $salesPaymentDetailTransfer = $this->salesPaymentDetailRepository->findByPaymentReference($paymentUpdatedTransfer->getPaymentReferenceOrFail());
        }

        if (!$salesPaymentDetailTransfer) {
            return;
        }

        $salesPaymentDetailTransfer->fromArray($paymentUpdatedTransfer->toArray(), true);

        $this->salesPaymentDetailEntityManager->updateSalesPaymentDetails($salesPaymentDetailTransfer);
    }
}
