<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Persistence;

use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Payolution\PayolutionConfig getConfig()
 * @method \Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface getQueryContainer()
 */
class PayolutionPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionQuery
     */
    public function createPaymentPayolutionQuery()
    {
        return SpyPaymentPayolutionQuery::create();
    }

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    public function createPaymentPayolutionTransactionStatusLogQuery()
    {
        return SpyPaymentPayolutionTransactionStatusLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery
     */
    public function createPaymentPayolutionTransactionRequestLogQuery()
    {
        return SpyPaymentPayolutionTransactionRequestLogQuery::create();
    }
}
