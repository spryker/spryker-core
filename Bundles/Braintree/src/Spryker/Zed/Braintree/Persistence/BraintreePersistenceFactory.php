<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Persistence;

use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeQuery;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLogQuery;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Braintree\BraintreeConfig getConfig()
 * @method \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainer getQueryContainer()
 */
class BraintreePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeQuery
     */
    public function createPaymentBraintreeQuery()
    {
        return SpyPaymentBraintreeQuery::create();
    }

    /**
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    public function createPaymentBraintreeTransactionStatusLogQuery()
    {
        return SpyPaymentBraintreeTransactionStatusLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLogQuery
     */
    public function createPaymentBraintreeTransactionRequestLogQuery()
    {
        return SpyPaymentBraintreeTransactionRequestLogQuery::create();
    }
}
