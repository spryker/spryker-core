<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Persistence;

use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 */
class RatepayPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function createPaymentRatepayQuery()
    {
        return SpyPaymentRatepayQuery::create();
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery
     */
    public function createPaymentRatepayLogQuery()
    {
        return SpyPaymentRatepayLogQuery::create();
    }
}
