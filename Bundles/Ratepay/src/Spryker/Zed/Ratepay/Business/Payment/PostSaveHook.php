<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;

class PostSaveHook implements PostSaveHookInterface
{
    /**
     * @var \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    public function __construct(
        RatepayQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $checkoutResponse = $this->checkPaymentRequestLogs($checkoutResponse);
        $checkoutResponse = $this->checkPaymentConfirmLogs($checkoutResponse);

        return $checkoutResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function checkPaymentRequestLogs(CheckoutResponseTransfer $checkoutResponse)
    {
        $logRecord = $this->queryContainer
            ->getLastLogRecordBySalesOrderIdAndMessage($checkoutResponse->getSaveOrder()->getIdSalesOrder(), ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST);
        if ($logRecord
            && $logRecord->getResponseResultCode() != ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST]
        ) {
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode($logRecord->getResponseResultCode())
                ->setMessage($logRecord->getResponseResultText());

            $checkoutResponse->addError($error);
            $checkoutResponse->setIsSuccess(false);
        }

        return $checkoutResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function checkPaymentConfirmLogs(CheckoutResponseTransfer $checkoutResponse)
    {
        $logRecord = $this->queryContainer
            ->getLastLogRecordBySalesOrderIdAndMessage($checkoutResponse->getSaveOrder()->getIdSalesOrder(), ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM);
        if ($logRecord
            && $logRecord->getResponseResultCode() != ApiConstants::REQUEST_CODE_SUCCESS_MATRIX[ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM]
        ) {
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode($logRecord->getResponseResultCode())
                ->setMessage($logRecord->getResponseResultText());

            $checkoutResponse->addError($error);
            $checkoutResponse->setIsSuccess(false);
        }

        return $checkoutResponse;
    }
}
