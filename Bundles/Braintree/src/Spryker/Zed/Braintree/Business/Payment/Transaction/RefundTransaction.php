<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Transaction;

use Braintree\Transaction as BraintreeTransaction;
use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;

class RefundTransaction extends AbstractTransaction
{

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManagerInterface
     */
    protected $currencyManager;

    /**
     * @param \Spryker\Zed\Braintree\BraintreeConfig $brainTreeConfig
     * @param \Spryker\Shared\Library\Currency\CurrencyManagerInterface $currencyManager
     */
    public function __construct(BraintreeConfig $brainTreeConfig, CurrencyManagerInterface $currencyManager)
    {
        parent::__construct($brainTreeConfig);

        $this->currencyManager = $currencyManager;
    }

    /**
     * @return string
     */
    protected function getTransactionType()
    {
        return ApiConstants::CREDIT;
    }

    /**
     * @return string
     */
    protected function getTransactionCode()
    {
        return ApiConstants::TRANSACTION_CODE_REFUND;
    }

    /**
     * @return \Braintree\Result\Error|\Braintree\Result\Successful
     */
    public function doTransaction()
    {
        return $this->refund();
    }

    /**
     * @return \Braintree\Result\Error|\Braintree\Result\Successful
     */
    protected function refund()
    {
        $transaction = $this->findTransaction();

        if ($transaction->status === ApiConstants::STATUS_CODE_CAPTURE_SUBMITTED) {
            return BraintreeTransaction::void($this->getTransactionIdentifier());
        }

        return BraintreeTransaction::refund(
            $this->getTransactionIdentifier(),
            $this->getAmount()
        );
    }

    /**
     * @return float|null
     */
    protected function getAmount()
    {
        $refundTransfer = $this->transactionMetaTransfer->requireRefund()->getRefund();
        if ($refundTransfer->getAmount() === null) {
            return null;
        }

        return $this->currencyManager->convertCentToDecimal($refundTransfer->getAmount());
    }

    /**
     * @return \Braintree\Transaction
     */
    protected function findTransaction()
    {
        return BraintreeTransaction::find($this->getTransactionIdentifier());
    }

}
