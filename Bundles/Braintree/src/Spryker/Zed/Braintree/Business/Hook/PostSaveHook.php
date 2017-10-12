<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Braintree\Business\Hook;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface;

class PostSaveHook implements PostSaveHookInterface
{
    /**
     * @var \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface $queryContainer
     */
    public function __construct(
        BraintreeQueryContainerInterface $queryContainer
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
        $queryLog = $this->queryContainer->queryTransactionStatusLogBySalesOrderId($checkoutResponse->getSaveOrder()->getIdSalesOrder());
        $logRecord = $queryLog->findOne();

        if ($logRecord && $logRecord->getCode() != ApiConstants::PAYMENT_CODE_AUTHORIZE_SUCCESS) {
            $errorTransfer = new CheckoutErrorTransfer();
            $errorTransfer
                ->setErrorCode($logRecord->getCode())
                ->setMessage($logRecord->getMessage());

            $checkoutResponse->addError($errorTransfer);
        }

        return $checkoutResponse;
    }
}
