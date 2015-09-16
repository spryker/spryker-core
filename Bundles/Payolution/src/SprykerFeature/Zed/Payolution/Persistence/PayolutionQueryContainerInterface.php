<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Persistence;

use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionQuery;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLogQuery;

interface PayolutionQueryContainerInterface
{

    /**
     * @param $idPayment
     *
     * @return SpyPaymentPayolution
     */
    public function queryPaymentById($idPayment);

    /**
     * @param $idPayment
     *
     * @return SpyPaymentPayolutionTransactionStatusLog
     */
    public function queryLatestItemOfTransactionStatusLogByPaymentId($idPayment);

    /**
     * @param int $idSalesOrder
     *
     * @return SpyPaymentPayolutionQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder);

    /**
     * @param int $idPayment
     * @param string $paymentCode
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryLatestItemOfTransactionStatusLogByPaymentIdAndPaymentCode($idPayment, $paymentCode);

}
