<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Persistence;

use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Payone\PayoneConfig getConfig()
 * @method \Spryker\Zed\Payone\Persistence\PayoneQueryContainer getQueryContainer()
 */
class PayonePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createPaymentPayoneTransactionStatusLogQuery()
    {
        return SpyPaymentPayoneTransactionStatusLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentPayoneQuery()
    {
        return SpyPaymentPayoneQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createPaymentPayoneApiLogQuery()
    {
        return SpyPaymentPayoneApiLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery
     */
    public function createPaymentPayoneTransactionStatusLogOrderItemQuery()
    {
        return SpyPaymentPayoneTransactionStatusLogOrderItemQuery::create();
    }

}
