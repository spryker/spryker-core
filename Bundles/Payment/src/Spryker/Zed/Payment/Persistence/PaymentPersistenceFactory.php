<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery;
use Orm\Zed\Payment\Persistence\SpySalesPaymentQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 */
class PaymentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentQuery
     */
    public function createSalesPaymentQuery()
    {
        return SpySalesPaymentQuery::create();
    }

    /**
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery
     */
    public function createSalesPaymentMethodTypeQuery()
    {
        return SpySalesPaymentMethodTypeQuery::create();
    }
}
