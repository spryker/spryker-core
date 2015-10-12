<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Persistence;

use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionQuery;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionRequestLogQuery;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLogQuery;

interface PayolutionQueryContainerInterface
{

    /**
     * @return SpyPaymentPayolutionQuery
     */
    public function queryPayments();

    /**
     * @param $idPayment
     *
     * @return SpyPaymentPayolutionQuery
     */
    public function queryPaymentById($idPayment);

    /**
     * @param int $idSalesOrder
     *
     * @return SpyPaymentPayolutionQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder);

    /**
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLog();

    /**
     * @param $idPayment
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogByPaymentId($idPayment);

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogByPaymentIdLatestFirst($idPayment);


    /**
     * @param $idSalesOrder
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderId($idSalesOrder);

    /**
     * @param int $idSalesOrder
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderIdLatestFirst($idSalesOrder);

    /**
     * @param int $idSalesOrder
     * @param string $paymentCode
     *
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderIdAndPaymentCodeLatestFirst($idSalesOrder, $paymentCode);

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolutionTransactionRequestLogQuery
     */
    public function queryTransactionRequestLogByPaymentId($idPayment);

}
