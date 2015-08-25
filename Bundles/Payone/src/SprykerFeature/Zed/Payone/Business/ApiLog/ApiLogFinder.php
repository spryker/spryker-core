<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\ApiLog;

use Generated\Shared\Payone\PayonePaymentInterface;
use Generated\Shared\Transfer\PayoneAuthorizationCheckResponseTransfer;
use Generated\Shared\Payone\OrderInterface;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog;

class ApiLogFinder
{

    /**
     * @var PayoneQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param PayoneQueryContainerInterface $queryContainer
     */
    public function __construct(PayoneQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationApproved(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationRedirect(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_REDIRECT
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationError(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_ERROR
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationRedirect(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_REDIRECT
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationError(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_ERROR
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_CAPTURE,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isCaptureError(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_CAPTURE,
            PayoneApiConstants::RESPONSE_TYPE_ERROR
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_REFUND,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundError(OrderInterface $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_REFUND,
            PayoneApiConstants::RESPONSE_TYPE_ERROR
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param string $request Relevant request
     * @param string $status Expected status
     *
     * @return bool
     */
    protected function hasApiLogStatus(OrderInterface $orderTransfer, $request, $status)
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrder();
        $apiLog = $this->queryContainer->getApiLogByOrderIdAndRequest($idSalesOrder, $request);

        if ($apiLog === null) {
            return false;
        }
        return $apiLog->getStatus() === $status;
    }

    /**
     * @param PayonePaymentInterface $payonePaymentTransfer
     *
     * @return PayoneAuthorizationCheckResponseTransfer
     */
    public function getAuthorizationResponse(PayonePaymentInterface $payonePaymentTransfer)
    {
        $response = new PayoneAuthorizationCheckResponseTransfer();

        $paymentEntity = $this->findPaymentByTransactionId($payonePaymentTransfer->getTransactionId());
        // no payment - no success - but: what if auth call was successfull but
        // exception occurred....?
        if (!$paymentEntity) {
            $response->setIsSuccess(false);
            // @todo define customer display message
            //$response->setCustomerErrorMessage($customerErrorMessage);

            return $response;
        }

        $apiLogEntity = $this->findApiLog($paymentEntity, $payonePaymentTransfer->getAuthorizationType());

        // no transaction id means it was not updated cause payment failed (or exception after call...)
        // but we should/may have api log with response
        if (!$paymentEntity->getTransactionId()) {
            $response->setIsSuccess(false);
            if ($apiLogEntity) {
                $response->setCustomerErrorMessage($apiLogEntity->getErrorMessageUser());
            } else {
                // @todo define customer display message
                //$response->setCustomerErrorMessage($customerErrorMessage);
            }

            return $response;
        }

        // we have transaction id, check api log. if we do not have api log entity we can not
        // say if it is a redirect! could check payment method.. cc/paypal/ideal... is dirty
        // what to do in this case
        if (!$apiLogEntity) {
            $response->setIsSuccess(false);
            // @todo define customer display message
            //$response->setCustomerErrorMessage($customerErrorMessage);

            return $response;
        }

        // we have transaction id in payment and api log
        if (
            // authorization is success on APPROVED and REDIRECT
            $apiLogEntity->getStatus() === PayoneApiConstants::RESPONSE_TYPE_APPROVED ||
            $apiLogEntity->getStatus() === PayoneApiConstants::RESPONSE_TYPE_REDIRECT
        ) {
            $response->setIsSuccess(true);
            $response->setRequest($apiLogEntity->getRequest());

            if ($apiLogEntity->getStatus() === PayoneApiConstants::RESPONSE_TYPE_REDIRECT) {
                $response->setIsRedirect(true);
                $response->setRedirectUrl($apiLogEntity->getRedirectUrl());
            }
        } else {
            $response->setIsSuccess(false);
            $response->setErrorCode($apiLogEntity->getErrorCode());
            $response->setCustomerErrorMessage($apiLogEntity->getErrorMessageUser());
            $response->setInternalErrorMessage($apiLogEntity->getErrorMessageInternal());
        }

        return $response;
    }

    /**
     * @param int $transactionId
     *
     * @return SpyPaymentPayone|null
     */
    protected function findPaymentByTransactionId($transactionId)
    {
        return $this->queryContainer->getPaymentByTransactionIdQuery($transactionId)->findOne();
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return SpyPaymentPayone
     */
    protected function findPaymentByOrder(OrderInterface $orderTransfer)
    {
        return $this->queryContainer->getPaymentByOrderId($orderTransfer->getIdSalesOrder())->findOne();
    }

    /**
     * @param SpyPaymentPayone $payment
     * @param $authorizationType
     *
     * @return SpyPaymentPayoneApiLog
     */
    protected function findApiLog(SpyPaymentPayone $payment, $authorizationType)
    {
        return $this->queryContainer->getApiLogByPaymentAndRequestTypeQuery(
            $payment->getPrimaryKey(),
            $authorizationType
        )->findOne();
    }

}
