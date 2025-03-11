<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Persistence;

use Orm\Zed\PaymentApp\Persistence\SpyPaymentAppPaymentStatusHistory;
use Orm\Zed\PaymentApp\Persistence\SpyPaymentAppPaymentStatusQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PaymentApp\Persistence\Mapper\PaymentAppPaymentStatusMapper;

/**
 * @method \Spryker\Zed\PaymentApp\Persistence\PaymentAppQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PaymentApp\PaymentAppConfig getConfig()
 * @method \Spryker\Zed\PaymentApp\Persistence\PaymentAppEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PaymentApp\Persistence\PaymentAppRepositoryInterface getRepository()
 */
class PaymentAppPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PaymentApp\Persistence\SpyPaymentAppPaymentStatusQuery
     */
    public function createPaymentAppPaymentStatusQuery(): SpyPaymentAppPaymentStatusQuery
    {
        return SpyPaymentAppPaymentStatusQuery::create();
    }

    /**
     * @return \Orm\Zed\PaymentApp\Persistence\SpyPaymentAppPaymentStatusHistory
     */
    public function createPaymentAppPaymentStatusHistory(): SpyPaymentAppPaymentStatusHistory
    {
        return new SpyPaymentAppPaymentStatusHistory();
    }

    /**
     * @return \Spryker\Zed\PaymentApp\Persistence\Mapper\PaymentAppPaymentStatusMapper
     */
    public function createPaymentAppPaymentStatusMapper(): PaymentAppPaymentStatusMapper
    {
        return new PaymentAppPaymentStatusMapper();
    }
}
