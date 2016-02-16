<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\ApiLog;

use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneAuthorizationCheckResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use Spryker\Shared\Payone\PayoneApiConstants;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;

class ApiLogFinder
{

    /**
     * @var \Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     */
    public function __construct(PayoneQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationRedirect(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_REDIRECT
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationError(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_ERROR
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationRedirect(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_REDIRECT
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationError(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_ERROR
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_CAPTURE,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureError(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_CAPTURE,
            PayoneApiConstants::RESPONSE_TYPE_ERROR
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_REFUND,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundError(OrderTransfer $orderTransfer)
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_REFUND,
            PayoneApiConstants::RESPONSE_TYPE_ERROR
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $request Relevant request
     * @param string $status Expected status
     *
     * @return bool
     */
    protected function hasApiLogStatus(OrderTransfer $orderTransfer, $request, $status)
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrder();
        $apiLog = $this->queryContainer->getApiLogsByOrderIdAndRequest($idSalesOrder, $request)->findOne();

        if ($apiLog === null) {
            return false;
        }

        return $apiLog->getStatus() === $status;
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePaymentTransfer $payonePaymentTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneAuthorizationCheckResponseTransfer
     */
    public function getAuthorizationResponse(PayonePaymentTransfer $payonePaymentTransfer)
    {
        $response = new PayoneAuthorizationCheckResponseTransfer();

        $paymentEntity = $this->findPaymentByTransactionId($payonePaymentTransfer->getTransactionId());
        // no payment - no success - but: what if auth call was successful but
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
        if (// authorization is success on APPROVED and REDIRECT
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
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function findPaymentByTransactionId($transactionId)
    {
        return $this->queryContainer->getPaymentByTransactionIdQuery($transactionId)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function findPaymentByOrder(OrderTransfer $orderTransfer)
    {
        return $this->queryContainer->getPaymentByOrderId($orderTransfer->getIdSalesOrder())->findOne();
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $payment
     * @param string $authorizationType
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog
     */
    protected function findApiLog(SpyPaymentPayone $payment, $authorizationType)
    {
        return $this->queryContainer->getApiLogByPaymentAndRequestTypeQuery(
            $payment->getPrimaryKey(),
            $authorizationType
        )->findOne();
    }

}
