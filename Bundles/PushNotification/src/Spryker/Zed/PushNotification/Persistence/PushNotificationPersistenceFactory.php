<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence;

use Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLogQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface;
use Spryker\Zed\PushNotification\Persistence\Mapper\PaginationMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationGroupMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationProviderMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationSubscriptionDeliveryLogMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationSubscriptionMapper;
use Spryker\Zed\PushNotification\PushNotificationDependencyProvider;

/**
 * @method \Spryker\Zed\PushNotification\PushNotificationConfig getConfig()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface getEntityManager()
 */
class PushNotificationPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery
     */
    public function createPushNotificationGroupQuery(): SpyPushNotificationGroupQuery
    {
        return SpyPushNotificationGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery
     */
    public function createPushNotificationProviderQuery(): SpyPushNotificationProviderQuery
    {
        return SpyPushNotificationProviderQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery
     */
    public function createPushNotificationSubscriptionQuery(): SpyPushNotificationSubscriptionQuery
    {
        return SpyPushNotificationSubscriptionQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLogQuery
     */
    public function createPushNotificationSubscriptionDeliveryLogQuery(): SpyPushNotificationSubscriptionDeliveryLogQuery
    {
        return SpyPushNotificationSubscriptionDeliveryLogQuery::create();
    }

    /**
     * @return \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationGroupMapper
     */
    public function createPushNotificationGroupMapper(): PushNotificationGroupMapper
    {
        return new PushNotificationGroupMapper();
    }

    /**
     * @return \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationSubscriptionMapper
     */
    public function createPushNotificationSubscriptionMapper(): PushNotificationSubscriptionMapper
    {
        return new PushNotificationSubscriptionMapper($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationMapper
     */
    public function createPushNotificationMapper(): PushNotificationMapper
    {
        return new PushNotificationMapper(
            $this->getUtilEncodingService(),
            $this->createPushNotificationGroupMapper(),
            $this->createPushNotificationProviderMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationProviderMapper
     */
    public function createPushNotificationProviderMapper(): PushNotificationProviderMapper
    {
        return new PushNotificationProviderMapper();
    }

    /**
     * @return \Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationSubscriptionDeliveryLogMapper
     */
    public function createPushNotificationSubscriptionDeliveryLogMapper(): PushNotificationSubscriptionDeliveryLogMapper
    {
        return new PushNotificationSubscriptionDeliveryLogMapper(
            $this->createPushNotificationMapper(),
            $this->createPushNotificationSubscriptionMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Persistence\Mapper\PaginationMapper
     */
    public function createPaginationMapper(): PaginationMapper
    {
        return new PaginationMapper();
    }

    /**
     * @return \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PushNotificationToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PushNotificationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery
     */
    public function createPushNotificationQuery(): SpyPushNotificationQuery
    {
        return SpyPushNotificationQuery::create();
    }
}
