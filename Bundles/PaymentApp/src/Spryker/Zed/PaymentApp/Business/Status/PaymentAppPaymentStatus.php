<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Business\Status;

use Generated\Shared\Transfer\PaymentAppPaymentStatusCriteriaTransfer;
use Generated\Shared\Transfer\PaymentAppPaymentStatusRequestTransfer;
use Generated\Shared\Transfer\PaymentAppPaymentStatusResponseTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\PaymentApp\Business\Mapper\PaymentMessageMapperInterface;
use Spryker\Zed\PaymentApp\Business\Reader\PaymentAppPaymentStatusReaderInterface;
use Spryker\Zed\PaymentApp\Business\Writer\PaymentAppPaymentStatusWriterInterface;
use Spryker\Zed\PaymentApp\Dependency\Service\PaymentAppToUtilEncodingServiceInterface;
use Spryker\Zed\PaymentApp\PaymentAppConfig;

class PaymentAppPaymentStatus implements PaymentAppPaymentStatusInterface
{
    use LoggerTrait;

    /**
     * @param \Spryker\Zed\PaymentApp\Business\Reader\PaymentAppPaymentStatusReaderInterface $paymentAppPaymentStatusReader
     * @param \Spryker\Zed\PaymentApp\Business\Writer\PaymentAppPaymentStatusWriterInterface $paymentAppPaymentStatusWriter
     * @param \Spryker\Zed\PaymentApp\Business\Mapper\PaymentMessageMapperInterface $paymentMessageMapper
     * @param \Spryker\Zed\PaymentApp\Dependency\Service\PaymentAppToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        protected PaymentAppPaymentStatusReaderInterface $paymentAppPaymentStatusReader,
        protected PaymentAppPaymentStatusWriterInterface $paymentAppPaymentStatusWriter,
        protected PaymentMessageMapperInterface $paymentMessageMapper,
        protected PaymentAppToUtilEncodingServiceInterface $utilEncodingService
    ) {
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $paymentAppMessageTransfer
     *
     * @return void
     */
    public function savePaymentAppPaymentStatus(AbstractTransfer $paymentAppMessageTransfer): void
    {
        $paymentAppStatusUpdatedTransfer = $this->paymentMessageMapper
            ->mapPaymentMessageTransferToPaymentAppStatusUpdatedTransfer($paymentAppMessageTransfer);

        $this->paymentAppPaymentStatusWriter->persistPaymentAppPaymentStatus($paymentAppStatusUpdatedTransfer);
        $this->paymentAppPaymentStatusWriter->persistPaymentAppPaymentStatusHistory($paymentAppStatusUpdatedTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAppPaymentStatusRequestTransfer $paymentAppPaymentStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAppPaymentStatusResponseTransfer
     */
    public function hasPaymentAppExpectedPaymentStatus(
        PaymentAppPaymentStatusRequestTransfer $paymentAppPaymentStatusRequestTransfer
    ): PaymentAppPaymentStatusResponseTransfer {
        $paymentAppPaymentStatusResponseTransfer = new PaymentAppPaymentStatusResponseTransfer();

        $paymentAppPaymentStatusCriteria = new PaymentAppPaymentStatusCriteriaTransfer();
        $paymentAppPaymentStatusCriteria->setOrderReferences([$paymentAppPaymentStatusRequestTransfer->getOrderReferenceOrFail()]);

        $paymentAppPaymentStatusCollection = $this->paymentAppPaymentStatusReader->getPaymentAppPaymentStatusCollection($paymentAppPaymentStatusCriteria);

        $paymentAppPaymentStates = $paymentAppPaymentStatusCollection->getPaymentAppPaymentStates();

        // This will be met when the payment is not made via an App OR when this command is used to early in the OMS.
        if (!$paymentAppPaymentStates->offsetExists(0)) {
            return $paymentAppPaymentStatusResponseTransfer->setIsInExpectedState(false);
        }

        /** @var \Generated\Shared\Transfer\PaymentAppPaymentStatusTransfer $paymentAppPaymentStatusTransfer */
        $paymentAppPaymentStatusTransfer = $paymentAppPaymentStates->offsetGet(0);
        $currentPaymentStatus = $paymentAppPaymentStatusTransfer->getStatusOrFail();

        // The status to check against
        $paymentStatusToCheck = $paymentAppPaymentStatusRequestTransfer->getStatusOrFail();

        // We either have a list of states that will consider a payment in the expected state or when we don't have it configured
        // as list, we will check against the expected passed status only
        // payment_overpaid, payment_underpaid, authorization_failed, capture_failed, cancellation_failed, are "end" states
        // where an action is required to get a new status for the payment, we can't have a list of states to check against
        $statusMap = PaymentAppConfig::STATUS_MAP[$paymentStatusToCheck] ?? [$paymentStatusToCheck];

        $isInArray = in_array($currentPaymentStatus, $statusMap);

        return $paymentAppPaymentStatusResponseTransfer->setIsInExpectedState($isInArray);
    }
}
