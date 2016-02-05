<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Persistence;

use Orm\Zed\Nopayment\Persistence\SpyNopaymentPaidQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Nopayment\NopaymentConfig getConfig()
 * @method \Spryker\Zed\Nopayment\Persistence\NopaymentQueryContainer getQueryContainer()
 */
class NopaymentPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Nopayment\Persistence\SpyNopaymentPaidQuery
     */
    public function createNopaymentPaidQuery()
    {
        return SpyNopaymentPaidQuery::create();
    }

}
