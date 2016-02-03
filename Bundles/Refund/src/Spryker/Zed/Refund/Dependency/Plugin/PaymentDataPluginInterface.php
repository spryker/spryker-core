<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDataTransfer;

interface PaymentDataPluginInterface
{

    /**
     * @param int $idOrder
     *
     * @return \Generated\Shared\Transfer\PaymentDataTransfer
     */
    public function getPaymentData($idOrder);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\PaymentDataTransfer $paymentData
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDataTransfer $paymentData, $idOrder);

}
