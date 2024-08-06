<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Persistence;

use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutQuery;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversalQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesPaymentMerchant\Persistence\Propel\Mapper\SalesPaymentMerchantPayoutMapper;
use Spryker\Zed\SalesPaymentMerchant\Persistence\Propel\Mapper\SalesPaymentMerchantPayoutReversalMapper;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig getConfig()
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantEntityManagerInterface getEntityManager()
 */
class SalesPaymentMerchantPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutQuery
     */
    public function getSalesPaymentMerchantPayoutQuery(): SpySalesPaymentMerchantPayoutQuery
    {
        return SpySalesPaymentMerchantPayoutQuery::create();
    }

    /**
     * @return \Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversalQuery
     */
    public function getSalesPaymentMerchantPayoutReversalQuery(): SpySalesPaymentMerchantPayoutReversalQuery
    {
        return SpySalesPaymentMerchantPayoutReversalQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Persistence\Propel\Mapper\SalesPaymentMerchantPayoutMapper
     */
    public function createSalesPaymentMerchantPayoutMapper(): SalesPaymentMerchantPayoutMapper
    {
        return new SalesPaymentMerchantPayoutMapper();
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Persistence\Propel\Mapper\SalesPaymentMerchantPayoutReversalMapper
     */
    public function createSalesPaymentMerchantPayoutReversalMapper(): SalesPaymentMerchantPayoutReversalMapper
    {
        return new SalesPaymentMerchantPayoutReversalMapper();
    }
}
