<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Handler\Transaction;

use Braintree\Configuration;
use Braintree\Exception\NotFound;
use Braintree\PaymentInstrumentType;
use Braintree\Transaction as BraintreeTransaction;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\BraintreeTransactionResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintree;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLog;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLog;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Braintree\Business\Payment\Handler\AbstractPaymentHandler;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface;

class Transaction extends AbstractPaymentHandler implements TransactionInterface
{

    /**
     * @var \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Braintree\BraintreeConfig $config
     */
    public function __construct(BraintreeQueryContainerInterface $queryContainer, BraintreeConfig $config)
    {
        parent::__construct($config);

        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function preCheckPayment(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = $quoteTransfer->getPayment()->getBraintree();

        $responseTransfer = new BraintreeTransactionResponseTransfer();

        if ($paymentTransfer === null) {
            $responseTransfer->setIsSuccess(false);
            $responseTransfer->setMessage('Invalid Payment Method');

            return $responseTransfer;
        }

        $response = $this->preCheck($quoteTransfer);

        $responseTransfer->setIsSuccess($response->success);

        if (!$response->success) {
            $paymentTransfer->setNonce('');
            $responseTransfer->setMessage($response->message);
            return $responseTransfer;
        }

        /** @var \Braintree\Transaction $transaction */
        $transaction = $response->transaction;
        $paymentTransfer->setPaymentType($transaction->paymentInstrumentType);

        if (!$this->isValidPaymentType($quoteTransfer->getPayment()->getPaymentSelection(), $transaction->paymentInstrumentType)) {
            $responseTransfer->setIsSuccess(false);
            $paymentTransfer->setNonce('');
            $responseTransfer->setMessage('Invalid Payment method type selected');

            return $responseTransfer;
        }

        if ($paymentTransfer->getPaymentType() === PaymentInstrumentType::PAYPAL_ACCOUNT) {
            $quoteTransfer->getPayment()->setPaymentMethod(PaymentTransfer::BRAINTREE_PAY_PAL);
            $quoteTransfer->getPayment()->setPaymentProvider(BraintreeConstants::PROVIDER_NAME);
            $quoteTransfer->getPayment()->setPaymentSelection(PaymentTransfer::BRAINTREE_PAY_PAL);
        } elseif ($paymentTransfer->getPaymentType() === PaymentInstrumentType::CREDIT_CARD) {
            $quoteTransfer->getPayment()->setPaymentMethod(PaymentTransfer::BRAINTREE_CREDIT_CARD);
            $quoteTransfer->getPayment()->setPaymentProvider(BraintreeConstants::PROVIDER_NAME);
            $quoteTransfer->getPayment()->setPaymentSelection(PaymentTransfer::BRAINTREE_CREDIT_CARD);
        } else {
            $responseTransfer->setIsSuccess(false);
            $paymentTransfer->setNonce('');
            $responseTransfer->setMessage('Invalid Payment type: ' . $paymentTransfer->getPaymentType());

            return $responseTransfer;
        }

        $responseTransfer->setCode($transaction->processorSettlementResponseCode);
        $responseTransfer->setTransactionId($transaction->id);
        $responseTransfer->setTransactionStatus($transaction->status);
        $responseTransfer->setTransactionType($transaction->type);
        $responseTransfer->setMerchantAccount($transaction->merchantAccountId);
        $responseTransfer->setCreditCardType($transaction->creditCardDetails->cardType);
        $responseTransfer->setPaymentType($transaction->paymentInstrumentType);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function authorizePayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);

        $this->logApiRequest($paymentEntity->getTransactionId(), ApiConstants::SALE, ApiConstants::TRANSACTION_CODE_AUTHORIZE, $idPayment);

        $transaction = $this->authorize($paymentEntity);

        $responseTransfer = new BraintreeTransactionResponseTransfer();
        $responseTransfer->setTransactionId($paymentEntity->getTransactionId());
        $responseTransfer->setTransactionCode(ApiConstants::TRANSACTION_CODE_AUTHORIZE);

        $isSuccess = $transaction && $transaction->processorResponseCode === ApiConstants::PAYMENT_CODE_AUTHORIZE_SUCCESS;
        $responseTransfer->setIsSuccess($isSuccess);

        if (!$isSuccess) {
            $responseTransfer->setMessage('Could not find payment with the transaction id ' . $paymentEntity->getTransactionId());
            $this->logApiResponse($responseTransfer, $idPayment);

            return $responseTransfer;
        }

        $responseTransfer->setCode($transaction->processorResponseCode);
        $responseTransfer->setMessage($transaction->processorResponseText);
        $responseTransfer->setProcessingTimestamp($transaction->createdAt->getTimestamp());
        $responseTransfer->setTransactionStatus($transaction->status);
        $responseTransfer->setTransactionType($transaction->type);
        $responseTransfer->setTransactionAmount($transaction->amount);
        $responseTransfer->setMerchantAccount($transaction->merchantAccountId);
        $this->logApiResponse($responseTransfer, $idPayment, $transaction->statusHistory);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function revertPayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);

        $this->logApiRequest($paymentEntity->getTransactionId(), ApiConstants::CREDIT, ApiConstants::TRANSACTION_CODE_REVERSAL, $idPayment);

        $response = $this->revert($paymentEntity);

        $responseTransfer = new BraintreeTransactionResponseTransfer();
        $responseTransfer->setTransactionId($paymentEntity->getTransactionId());
        $responseTransfer->setTransactionCode(ApiConstants::TRANSACTION_CODE_REVERSAL);
        $responseTransfer->setIsSuccess($response->success);

        if (!$response->success) {
            $responseTransfer->setMessage($response->message);
            $this->logApiResponse($responseTransfer, $paymentEntity->getIdPaymentBraintree());

            return $responseTransfer;
        }

        $responseTransfer->setCode($response->transaction->processorResponseCode);
        $responseTransfer->setMessage($response->transaction->processorResponseText);
        $responseTransfer->setProcessingTimestamp($response->transaction->createdAt->getTimestamp());
        $responseTransfer->setTransactionStatus($response->transaction->status);
        $responseTransfer->setTransactionType($response->transaction->type);
        $responseTransfer->setTransactionAmount($response->transaction->amount);
        $responseTransfer->setMerchantAccount($response->transaction->merchantAccountId);

        $this->logApiResponse($responseTransfer, $idPayment, $response->transaction->statusHistory);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function capturePayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);

        $this->logApiRequest($paymentEntity->getTransactionId(), ApiConstants::SALE, ApiConstants::TRANSACTION_CODE_CAPTURE, $idPayment);

        $response = $this->capture($paymentEntity);

        $responseTransfer = new BraintreeTransactionResponseTransfer();
        $responseTransfer->setTransactionId($paymentEntity->getTransactionId());
        $responseTransfer->setTransactionCode(ApiConstants::TRANSACTION_CODE_CAPTURE);
        $responseTransfer->setIsSuccess($response->success);

        if (!$response->success) {
            $responseTransfer->setMessage($response->message);
            $this->logApiResponse($responseTransfer, $idPayment);

            return $responseTransfer;
        }

        $responseTransfer->setCode($response->transaction->processorResponseCode);
        $responseTransfer->setMessage($response->transaction->processorResponseText);
        $responseTransfer->setProcessingTimestamp($response->transaction->createdAt->getTimestamp());
        $responseTransfer->setTransactionStatus($response->transaction->status);
        $responseTransfer->setTransactionType($response->transaction->type);
        $responseTransfer->setTransactionAmount($response->transaction->amount);
        $responseTransfer->setMerchantAccount($response->transaction->merchantAccountId);
        $this->logApiResponse($responseTransfer, $idPayment, $response->transaction->statusHistory);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function refundPayment(OrderTransfer $orderTransfer, $idPayment)
    {
        $paymentEntity = $this->getPaymentEntity($idPayment);

        $this->logApiRequest($paymentEntity->getTransactionId(), ApiConstants::CREDIT, ApiConstants::TRANSACTION_CODE_REFUND, $idPayment);

        $response = $this->refund($paymentEntity);

        $responseTransfer = new BraintreeTransactionResponseTransfer();
        $responseTransfer->setTransactionId($paymentEntity->getTransactionId());
        $responseTransfer->setTransactionCode(ApiConstants::TRANSACTION_CODE_REFUND);
        $responseTransfer->setIsSuccess($response->success);

        if (!$response->success) {
            $responseTransfer->setMessage($response->message);
            $this->logApiResponse($responseTransfer, $idPayment);

            return $responseTransfer;
        }

        $responseTransfer->setCode($response->transaction->processorResponseCode);
        $responseTransfer->setMessage($response->transaction->processorResponseText);
        $responseTransfer->setProcessingTimestamp($response->transaction->createdAt->getTimestamp());
        $responseTransfer->setTransactionStatus($response->transaction->status);
        $responseTransfer->setTransactionType($response->transaction->type);
        $responseTransfer->setTransactionAmount($response->transaction->amount);
        $responseTransfer->setMerchantAccount($response->transaction->merchantAccountId);
        $this->logApiResponse($responseTransfer, $idPayment, $response->transaction->statusHistory);

        return $responseTransfer;
    }

    /**
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree
     */
    protected function getPaymentEntity($idPayment)
    {
        return $this->queryContainer->queryPaymentById($idPayment)->findOne();
    }

    /**
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLog
     */
    protected function getLatestTransactionStatusLogItem($idPayment)
    {
        return $this
            ->queryContainer
            ->queryTransactionStatusLogByPaymentIdLatestFirst($idPayment)
            ->findOne();
    }

    /**
     * @param string $transactionId
     * @param string $transactionType
     * @param string $transactionCode
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLog
     */
    protected function logApiRequest($transactionId, $transactionType, $transactionCode, $idPayment)
    {
        $logEntity = new SpyPaymentBraintreeTransactionRequestLog();
        $logEntity
            ->setTransactionId($transactionId)
            ->setTransactionType($transactionType)
            ->setTransactionCode($transactionCode)
            ->setFkPaymentBraintree($idPayment);
        $logEntity->save();

        return $logEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer $responseTransfer
     * @param int $idPayment
     * @param array $logs
     *
     * @return void
     */
    protected function logApiResponse(BraintreeTransactionResponseTransfer $responseTransfer, $idPayment, array $logs = [])
    {
        if (count($logs) > 0) {
            $log = array_pop($logs);
            $responseTransfer->setTransactionStatus($log->status);
            $responseTransfer->setTransactionAmount($log->amount);
            $responseTransfer->setProcessingTimestamp($log->timestamp->getTimestamp());
        }

        $logEntity = new SpyPaymentBraintreeTransactionStatusLog();
        $logEntity->fromArray($responseTransfer->toArray());
        $logEntity->setFkPaymentBraintree($idPayment);
        $logEntity->save();
    }

    /**
     * @return void
     */
    protected function initializeBraintree()
    {
        Configuration::environment($this->config->getEnvironment());
        Configuration::merchantId($this->config->getMerchantId());
        Configuration::publicKey($this->config->getPublicKey());
        Configuration::privateKey($this->config->getPrivateKey());
    }

    /**
     * @param string $postedSelection
     * @param string $returnedType
     *
     * @return bool
     */
    protected function isValidPaymentType($postedSelection, $returnedType)
    {
        $matching = [
            BraintreeConstants::PAYMENT_METHOD_PAY_PAL => PaymentInstrumentType::PAYPAL_ACCOUNT,
            BraintreeConstants::PAYMENT_METHOD_CREDIT_CARD => PaymentInstrumentType::CREDIT_CARD,
        ];
        return ($matching[$postedSelection] === $returnedType);
    }

    /**
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     *
     * @return \Braintree\Transaction|null
     */
    protected function authorize(SpyPaymentBraintree $paymentEntity)
    {
        $this->initializeBraintree();

        try {
            $transaction = BraintreeTransaction::find($paymentEntity->getTransactionId());
        } catch (NotFound $e) {
            return null;
        }

        return $transaction;
    }

    /**
     * For status of authorized or submittedForSettlement.
     *
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     *
     * @return \Braintree\Result\Successful|\Braintree\Result\Error
     */
    protected function revert(SpyPaymentBraintree $paymentEntity)
    {
        $this->initializeBraintree();

        return BraintreeTransaction::void($paymentEntity->getTransactionId());
    }

    /**
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     *
     * @return \Braintree\Result\Error|\Braintree\Result\Successful
     */
    protected function capture(SpyPaymentBraintree $paymentEntity)
    {
        $this->initializeBraintree();

        return BraintreeTransaction::submitForSettlement($paymentEntity->getTransactionId());
    }

    /**
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     *
     * @return \Braintree\Result\Error|\Braintree\Result\Successful
     */
    protected function refund(SpyPaymentBraintree $paymentEntity)
    {
        $transactionId = $paymentEntity->getTransactionId();

        $this->initializeBraintree();

        $transaction = BraintreeTransaction::find($transactionId);
        if ($transaction->status === ApiConstants::STATUS_CODE_CAPTURE_SUBMITTED) {
            $response = BraintreeTransaction::void($transactionId);
        } else {
            $response = BraintreeTransaction::refund($transactionId);
        }

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Braintree\Result\Error|\Braintree\Result\Successful
     */
    protected function preCheck(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = $quoteTransfer->getPayment()->getBraintree();
        $this->initializeBraintree();

        return BraintreeTransaction::sale([
            'amount' => $quoteTransfer->getTotals()->getGrandTotal() / 100,
            'paymentMethodNonce' => $paymentTransfer->getNonce(),
            'options' => $this->getRequestOptions(),
            'customer' => $this->getCustomerData($quoteTransfer),
            'billing' => $this->getCustomerAddressData($quoteTransfer->getBillingAddress()),
            'shipping' => $this->getCustomerAddressData($quoteTransfer->getShippingAddress()),
        ]);
    }

    /**
     * @return array
     */
    protected function getRequestOptions()
    {
        return [
            'threeDSecure' => [
                'required' => $this->config->getIs3DSecure()
            ],
            'storeInVault' => $this->config->getIsVaulted()
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getCustomerData(QuoteTransfer $quoteTransfer)
    {
        return [
            'firstName' => $quoteTransfer->getCustomer()->getFirstName(),
            'lastName' => $quoteTransfer->getCustomer()->getLastName(),
            'email' => $quoteTransfer->getCustomer()->getEmail(),

            'company' => $quoteTransfer->getBillingAddress()->getCompany(),
            'phone' => $quoteTransfer->getBillingAddress()->getPhone(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return array
     */
    protected function getCustomerAddressData(AddressTransfer $addressTransfer)
    {
        return [
            'firstName' => $addressTransfer->getFirstName(),
            'lastName' => $addressTransfer->getLastName(),
            'company' => $addressTransfer->getCompany(),
            'streetAddress' => trim(sprintf('%s %s', $addressTransfer->getAddress1(), $addressTransfer->getAddress2())),
            'extendedAddress' => $addressTransfer->getAddress3(),
            'locality' => $addressTransfer->getCity(),
            'region' => $addressTransfer->getRegion(),
            'postalCode' => $addressTransfer->getZipCode(),
            'countryCodeAlpha2' => $addressTransfer->getIso2Code()
        ];
    }

}
