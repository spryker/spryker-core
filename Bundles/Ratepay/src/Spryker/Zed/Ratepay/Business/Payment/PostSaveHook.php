<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Payment;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants;
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
        $queryLog = $this->queryContainer->queryPaymentLogQueryBySalesOrderId($checkoutResponse->getSaveOrder()->getIdSalesOrder());
        $logRecord = $queryLog->findOne();

        if ($logRecord && $logRecord->getMessage() != Constants::REQUEST_MODEL_PAYMENT_CONFIRM) {
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode($logRecord->getResponseResultCode())
                ->setMessage($logRecord->getResponseResultText());

            $checkoutResponse->addError($error);
        }

        return $checkoutResponse;
    }

}
