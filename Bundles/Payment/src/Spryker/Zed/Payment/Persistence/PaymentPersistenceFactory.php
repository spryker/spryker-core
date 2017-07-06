<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesPaymentMethodTypeQuery;
use Orm\Zed\Sales\Persistence\SpySalesPaymentQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainer getQueryContainer()
 */
class PaymentPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesPaymentQuery
     */
    public function createSalesPaymentQuery()
    {
        return SpySalesPaymentQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesPaymentMethodTypeQuery
     */
    public function createSalesPaymentMethodTypeQuery()
    {
        return SpySalesPaymentMethodTypeQuery::create();
    }

}
