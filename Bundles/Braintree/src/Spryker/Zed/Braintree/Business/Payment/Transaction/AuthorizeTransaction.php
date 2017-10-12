<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Transaction;

use Braintree\Exception\NotFound;
use Braintree\Result\Error;
use Braintree\Result\Successful;
use Braintree\Transaction as BraintreeTransaction;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;

class AuthorizeTransaction extends AbstractTransaction
{
    /**
     * @return string
     */
    protected function getTransactionType()
    {
        return ApiConstants::SALE;
    }

    /**
     * @return string
     */
    protected function getTransactionCode()
    {
        return ApiConstants::TRANSACTION_CODE_AUTHORIZE;
    }

    /**
     * @return \Braintree\Result\Error|\Braintree\Transaction
     */
    public function doTransaction()
    {
        return $this->authorize();
    }

    /**
     * @param \Braintree\Transaction $response
     *
     * @return bool
     */
    protected function isTransactionSuccessful($response)
    {
        return ($response->success && $response->transaction->processorResponseCode === ApiConstants::PAYMENT_CODE_AUTHORIZE_SUCCESS);
    }

    /**
     * @return \Braintree\Result\Successful|\Braintree\Result\Error
     */
    protected function authorize()
    {
        try {
            $transaction = $this->findTransaction();
        } catch (NotFound $e) {
            $message = sprintf('Could not find payment with the transaction id "%s"', $this->getTransactionIdentifier());

            return new Error(['message' => $message, 'errors' => []]);
        }

        return new Successful($transaction);
    }

    /**
     * @return \Braintree\Transaction
     */
    protected function findTransaction()
    {
        return BraintreeTransaction::find($this->getTransactionIdentifier());
    }
}
