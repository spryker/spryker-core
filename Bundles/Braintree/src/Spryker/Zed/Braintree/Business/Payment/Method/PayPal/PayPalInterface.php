<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Method\PayPal;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintree;

interface PayPalInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function buildPreCheckRequest(QuoteTransfer $quoteTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRevertRequest(OrderTransfer $orderTransfer, SpyPaymentBraintree $paymentEntity, $uniqueId);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildCaptureRequest(OrderTransfer $orderTransfer, SpyPaymentBraintree $paymentEntity, $uniqueId);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRefundRequest(OrderTransfer $orderTransfer, SpyPaymentBraintree $paymentEntity, $uniqueId);

    /**
     * @return string
     */
    public function getMethodType();

}
