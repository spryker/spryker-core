<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Persistence;

use Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetailQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesPaymentDetail\Persistence\Propel\Mapper\SalesPaymentDetailMapper;

/**
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesPaymentDetail\SalesPaymentDetailConfig getConfig()
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailRepositoryInterface getRepository()
 */
class SalesPaymentDetailPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\SalesPaymentDetail\Persistence\Propel\Mapper\SalesPaymentDetailMapper
     */
    public function createSalesPaymentDetailMapper(): SalesPaymentDetailMapper
    {
        return new SalesPaymentDetailMapper();
    }

    /**
     * @return \Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetailQuery
     */
    public function createSalesPaymentDetailQuery(): SpySalesPaymentDetailQuery
    {
        return SpySalesPaymentDetailQuery::create();
    }
}
