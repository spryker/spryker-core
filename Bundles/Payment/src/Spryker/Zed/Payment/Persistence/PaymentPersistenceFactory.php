<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStoreQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentProviderQuery;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery;
use Orm\Zed\Payment\Persistence\SpySalesPaymentQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Payment\Persistence\Propel\Mapper\PaymentMapper;
use Spryker\Zed\Payment\Persistence\Propel\Mapper\PaymentProviderMapper;
use Spryker\Zed\Payment\Persistence\Propel\Mapper\StoreRelationMapper;

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
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery
     */
    public function createPaymentMethodQuery(): SpyPaymentMethodQuery
    {
        return SpyPaymentMethodQuery::create();
    }

    /**
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentMethodStoreQuery
     */
    public function createPaymentMethodStoreQuery(): SpyPaymentMethodStoreQuery
    {
        return SpyPaymentMethodStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\Payment\Persistence\Propel\Mapper\PaymentMapper
     */
    public function createPaymentMapper(): PaymentMapper
    {
        return new PaymentMapper(
            $this->createPaymentProviderMapper(),
            $this->createStoreRelationMapper()
        );
    }

    /**
     * @return \Spryker\Zed\Payment\Persistence\Propel\Mapper\PaymentProviderMapper
     */
    public function createPaymentProviderMapper(): PaymentProviderMapper
    {
        return new PaymentProviderMapper();
    }

    /**
     * @return \Spryker\Zed\Payment\Persistence\Propel\Mapper\StoreRelationMapper
     */
    public function createStoreRelationMapper(): StoreRelationMapper
    {
        return new StoreRelationMapper();
    }

    /**
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentProviderQuery
     */
    public function createPaymentProviderQuery(): SpyPaymentProviderQuery
    {
        return SpyPaymentProviderQuery::create();
    }
}
