<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentPersistenceFactory getFactory()
 */
interface PaymentQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idSalesOrder
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentQuery
     */
    public function queryPaymentMethodPriceToPay($idSalesOrder, $paymentProvider, $paymentMethod);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery
     */
    public function queryPaymentMethodType($paymentProvider, $paymentMethod);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentQuery
     */
    public function queryPaymentMethodsByIdSalesOrder($idSalesOrder);
}
