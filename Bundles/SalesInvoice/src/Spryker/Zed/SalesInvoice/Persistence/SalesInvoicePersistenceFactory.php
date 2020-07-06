<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Persistence;

use Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoiceQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\SalesInvoice\SalesInvoiceConfig getConfig()
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface getRepository()
 */
class SalesInvoicePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoiceQuery
     */
    public function getSalesOrderInvoicePropelQuery(): SpySalesOrderInvoiceQuery
    {
        return SpySalesOrderInvoiceQuery::create();
    }
}
