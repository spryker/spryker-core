<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Braintree\Persistence\BraintreePersistenceFactory getFactory()
 */
class BraintreeQueryContainer extends AbstractQueryContainer implements BraintreeQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeQuery
     */
    public function queryPayments()
    {
        return $this->getFactory()->createPaymentBraintreeQuery();
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeQuery
     */
    public function queryPaymentById($idPayment)
    {
        return $this
            ->queryPayments()
            ->filterByIdPaymentBraintree($idPayment);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder)
    {
        return $this
            ->queryPayments()
            ->filterByFkSalesOrder($idSalesOrder);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    public function queryTransactionStatusLog()
    {
        return $this->getFactory()->createPaymentBraintreeTransactionStatusLogQuery();
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogByPaymentId($idPayment)
    {
        return $this
            ->queryTransactionStatusLog()
            ->filterByFkPaymentBraintree($idPayment);
            //->filterByProcessingCode(BraintreeConstants::SUCCESSFUL_PRE_AUTHORIZATION_PROCESSING_CODE);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogByPaymentIdLatestFirst($idPayment)
    {
        return $this
            ->queryTransactionStatusLogByPaymentId($idPayment)
            ->orderByIdPaymentBraintreeTransactionStatusLog(Criteria::DESC);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderId($idSalesOrder)
    {
        return $this
            ->queryTransactionStatusLog()
            ->useSpyPaymentBraintreeQuery()
                ->filterByFkSalesOrder($idSalesOrder)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderIdLatestFirst($idSalesOrder)
    {
        return $this
            ->queryTransactionStatusLogBySalesOrderId($idSalesOrder)
            ->orderByIdPaymentBraintreeTransactionStatusLog(Criteria::DESC);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param string $paymentCode
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    public function queryTransactionStatusLogBySalesOrderIdAndPaymentCodeLatestFirst($idSalesOrder, $paymentCode)
    {
        return $this->queryTransactionStatusLogBySalesOrderIdLatestFirst($idSalesOrder)
            // Payment code need to get checked in request log table
            ->joinSpyPaymentBraintreeTransactionRequestLog()
            ->useSpyPaymentBraintreeTransactionRequestLogQuery()
            ->filterByPaymentCode($paymentCode)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLogQuery
     */
    public function queryTransactionRequestLogByPaymentId($idPayment)
    {
        $query = $this->getFactory()->createPaymentBraintreeTransactionRequestLogQuery();

        return $query->filterByFkPaymentBraintree($idPayment);
    }

}
