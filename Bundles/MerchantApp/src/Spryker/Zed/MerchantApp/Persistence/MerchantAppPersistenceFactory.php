<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Persistence;

use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingQuery;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatusQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantApp\Dependency\Service\MerchantAppToUtilEncodingServiceInterface;
use Spryker\Zed\MerchantApp\MerchantAppDependencyProvider;
use Spryker\Zed\MerchantApp\Persistence\Propel\Mapper\MerchantAppOnboardingMapper;
use Spryker\Zed\MerchantApp\Persistence\Propel\Mapper\MerchantAppOnboardingStatusMapper;
use Spryker\Zed\MerchantApp\Persistence\Propel\Mapper\ReadyForMerchantAppOnboardingMapper;

/**
 * @method \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantApp\MerchantAppConfig getConfig()
 * @method \Spryker\Zed\MerchantApp\Persistence\MerchantAppRepositoryInterface getRepository()
 */
class MerchantAppPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\MerchantApp\Persistence\Propel\Mapper\ReadyForMerchantAppOnboardingMapper
     */
    public function createReadyForMerchantAppOnboardingMapper(): ReadyForMerchantAppOnboardingMapper
    {
        return new ReadyForMerchantAppOnboardingMapper(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Persistence\Propel\Mapper\MerchantAppOnboardingStatusMapper
     */
    public function createMerchantAppOnboardingStatusMapper(): MerchantAppOnboardingStatusMapper
    {
        return new MerchantAppOnboardingStatusMapper();
    }

    /**
     * @return \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatusQuery
     */
    public function createMerchantAppOnboardingStatusQuery(): SpyMerchantAppOnboardingStatusQuery
    {
        return SpyMerchantAppOnboardingStatusQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingQuery
     */
    public function createMerchantAppOnboardingQuery(): SpyMerchantAppOnboardingQuery
    {
        return SpyMerchantAppOnboardingQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Persistence\Propel\Mapper\MerchantAppOnboardingMapper
     */
    public function createMerchantAppOnboardingMapper(): MerchantAppOnboardingMapper
    {
        return new MerchantAppOnboardingMapper($this->createMerchantAppOnboardingStatusMapper());
    }

    /**
     * @return \Spryker\Zed\MerchantApp\Dependency\Service\MerchantAppToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): MerchantAppToUtilEncodingServiceInterface
    {
        /** @phpstan-var \Spryker\Zed\MerchantApp\Dependency\Service\MerchantAppToUtilEncodingServiceInterface */
        return $this->getProvidedDependency(MerchantAppDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
