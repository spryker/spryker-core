<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig getConfig()
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface getRepository()
 */
class PriceProductSchedulePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    public function createPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return SpyPriceProductScheduleQuery::create();
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleMapperInterface
     */
    public function createPriceProductScheduleMapper(): PriceProductScheduleMapperInterface
    {
        return new PriceProductScheduleMapper(
            $this->getCurrencyFacade(),
            $this->getProductFacade(),
            $this->createPriceProductScheduleListMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleListMapperInterface
     */
    public function createPriceProductScheduleListMapper(): PriceProductScheduleListMapperInterface
    {
        return new PriceProductScheduleListMapper();
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): PriceProductScheduleToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface
     */
    public function getProductFacade(): PriceProductScheduleToProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleDependencyProvider::FACADE_PRODUCT);
    }
}
