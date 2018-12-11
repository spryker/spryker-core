<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery;
use Orm\Zed\Payment\Persistence\SpySalesPaymentQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Payment\Persistence\Propel\Mapper\PaymentMapper;
use Spryker\Zed\Payment\Persistence\Propel\Mapper\PaymentMapperInterface;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 * @method \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface getRepository()
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

    /**
     * @return \Spryker\Zed\Payment\Persistence\Propel\Mapper\PaymentMapperInterface
     */
    public function createPaymentMapper(): PaymentMapperInterface
    {
        return new PaymentMapper();
    }
}
