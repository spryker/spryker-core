<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\Disable\PriceProductScheduleDisableFinder;
use Spryker\Zed\PriceProductSchedule\Persistence\Disable\PriceProductScheduleDisableFinderInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\Enable\PriceProductScheduleEnableFinder;
use Spryker\Zed\PriceProductSchedule\Persistence\Enable\PriceProductScheduleEnableFinderInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\Finder\PriceProductScheduleFinder;
use Spryker\Zed\PriceProductSchedule\Persistence\Finder\PriceProductScheduleFinderInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\Finder\PriceProductScheduleListFinder;
use Spryker\Zed\PriceProductSchedule\Persistence\Finder\PriceProductScheduleListFinderInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapper;
use Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapperInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapper;
use Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig getConfig()
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface getRepository()
 */
class PriceProductSchedulePersistenceFactory extends AbstractPersistenceFactory implements PriceProductSchedulePersistenceFactoryInterface
{
    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    public function createPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return SpyPriceProductScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery
     */
    public function createPriceProductScheduleListQuery(): SpyPriceProductScheduleListQuery
    {
        return SpyPriceProductScheduleListQuery::create();
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface
     */
    public function createPriceProductScheduleMapper(): PriceProductScheduleMapperInterface
    {
        return new PriceProductScheduleMapper(
            $this->createPriceProductScheduleListMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapperInterface
     */
    public function createPriceProductScheduleListMapper(): PriceProductScheduleListMapperInterface
    {
        return new PriceProductScheduleListMapper();
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Persistence\Disable\PriceProductScheduleDisableFinderInterface
     */
    public function createPriceProductScheduleDisableFinder(): PriceProductScheduleDisableFinderInterface
    {
        return new PriceProductScheduleDisableFinder(
            $this->createPriceProductScheduleQuery(),
            $this->createPriceProductScheduleMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Persistence\Enable\PriceProductScheduleEnableFinderInterface
     */
    public function createPriceProductScheduleEnableFinder(): PriceProductScheduleEnableFinderInterface
    {
        return new PriceProductScheduleEnableFinder(
            $this->getPropelFacade(),
            $this,
            $this->getConfig(),
            $this->createPriceProductScheduleMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Persistence\Finder\PriceProductScheduleFinderInterface
     */
    public function createPriceProductScheduleFinder(): PriceProductScheduleFinderInterface
    {
        return new PriceProductScheduleFinder(
            $this->createPriceProductScheduleQuery(),
            $this->createPriceProductScheduleMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Persistence\Finder\PriceProductScheduleListFinderInterface
     */
    public function createPriceProductScheduleListFinder(): PriceProductScheduleListFinderInterface
    {
        return new PriceProductScheduleListFinder(
            $this->createPriceProductScheduleListQuery(),
            $this->createPriceProductScheduleListMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface
     */
    public function getPropelFacade(): PriceProductScheduleToPropelFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleDependencyProvider::FACADE_PROPEL);
    }
}
