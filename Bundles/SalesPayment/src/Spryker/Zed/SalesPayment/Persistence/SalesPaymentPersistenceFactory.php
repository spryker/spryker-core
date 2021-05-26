<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Persistence;

use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery;
use Orm\Zed\Payment\Persistence\SpySalesPaymentQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesPayment\Persistence\Propel\Mapper\SalesPaymentMapper;

/**
 * @method \Spryker\Zed\SalesPayment\SalesPaymentConfig getConfig()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface getEntityManager()
 */
class SalesPaymentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @phpstan-return \Orm\Zed\Payment\Persistence\SpySalesPaymentQuery<\Orm\Zed\Payment\Persistence\SpySalesPayment>
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentQuery
     */
    public function createSalesPaymentQuery(): SpySalesPaymentQuery
    {
        return SpySalesPaymentQuery::create();
    }

    /**
     * @phpstan-return \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery<\Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType>
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery
     */
    public function createSalesPaymentMethodTypeQuery(): SpySalesPaymentMethodTypeQuery
    {
        return SpySalesPaymentMethodTypeQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Persistence\Propel\Mapper\SalesPaymentMapper
     */
    public function createSalesPaymentMapper(): SalesPaymentMapper
    {
        return new SalesPaymentMapper();
    }
}
