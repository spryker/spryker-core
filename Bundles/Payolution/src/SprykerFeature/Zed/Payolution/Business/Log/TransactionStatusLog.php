<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Log;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLogQuery;

class TransactionStatusLog implements TransactionStatusLogInterface
{

    /**
     * @var PayolutionQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param PayolutionQueryContainerInterface $queryContainer
     */
    public function __construct(PayolutionQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED
        );
    }

    private function hasTransactionLogStatus(OrderTransfer $orderTransfer, $requestType, $exectedResponse)
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrder();
        $paymentEntity = $this->queryContainer->queryPaymentBySalesOrderId($idSalesOrder)->findOne();


        $this->queryContainer->queryLatestItemOfTransactionStatusLogByPaymentIdAndPaymentCode(
            $paymentEntity->getIdPaymentPayolution(),

        );
    }

}
