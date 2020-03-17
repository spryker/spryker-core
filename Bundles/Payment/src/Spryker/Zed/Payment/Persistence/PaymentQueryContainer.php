<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentPersistenceFactory getFactory()
 */
class PaymentQueryContainer extends AbstractQueryContainer implements PaymentQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentQuery
     */
    public function queryPaymentMethodPriceToPay($idSalesOrder, $paymentProvider, $paymentMethod)
    {
        return $this->getFactory()
            ->createSalesPaymentQuery()
            ->useSalesPaymentMethodTypeQuery()
                ->filterByPaymentProvider($paymentProvider)
                ->filterByPaymentMethod($paymentMethod)
            ->endUse()
            ->filterByFkSalesOrder($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery
     */
    public function queryPaymentMethodType($paymentProvider, $paymentMethod)
    {
        return $this->getFactory()
            ->createSalesPaymentMethodTypeQuery()
            ->filterByPaymentMethod($paymentMethod)
            ->filterByPaymentProvider($paymentProvider);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentQuery
     */
    public function queryPaymentMethodsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createSalesPaymentQuery()
            ->joinWithSalesPaymentMethodType()
            ->filterByFkSalesOrder($idSalesOrder);
    }
}
