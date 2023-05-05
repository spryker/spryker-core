<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ServicePointSearch\Business\DataMapper\ServicePointSearchDataMapper;
use Spryker\Zed\ServicePointSearch\Business\DataMapper\ServicePointSearchDataMapperInterface;
use Spryker\Zed\ServicePointSearch\Business\Deleter\ServicePointSearchDeleter;
use Spryker\Zed\ServicePointSearch\Business\Deleter\ServicePointSearchDeleterInterface;
use Spryker\Zed\ServicePointSearch\Business\Mapper\ServicePointSearchMapper;
use Spryker\Zed\ServicePointSearch\Business\Mapper\ServicePointSearchMapperInterface;
use Spryker\Zed\ServicePointSearch\Business\Writer\ServicePointSearchWriter;
use Spryker\Zed\ServicePointSearch\Business\Writer\ServicePointSearchWriterInterface;
use Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToServicePointFacadeInterface;
use Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToStoreFacadeInterface;
use Spryker\Zed\ServicePointSearch\Dependency\Service\ServicePointSearchToUtilEncodingServiceInterface;
use Spryker\Zed\ServicePointSearch\ServicePointSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ServicePointSearch\ServicePointSearchConfig getConfig()
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchRepositoryInterface getRepository()
 */
class ServicePointSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ServicePointSearch\Business\Writer\ServicePointSearchWriterInterface
     */
    public function createServicePointSearchWriter(): ServicePointSearchWriterInterface
    {
        return new ServicePointSearchWriter(
            $this->getServicePointFacade(),
            $this->getStoreFacade(),
            $this->getEventBehaviorFacade(),
            $this->createServicePointSearchMapper(),
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePointSearch\Business\Deleter\ServicePointSearchDeleterInterface
     */
    public function createServicePointSearchDeleter(): ServicePointSearchDeleterInterface
    {
        return new ServicePointSearchDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePointSearch\Business\DataMapper\ServicePointSearchDataMapperInterface
     */
    public function createServicePointSearchDataMapper(): ServicePointSearchDataMapperInterface
    {
        return new ServicePointSearchDataMapper();
    }

    /**
     * @return \Spryker\Zed\ServicePointSearch\Business\Mapper\ServicePointSearchMapperInterface
     */
    public function createServicePointSearchMapper(): ServicePointSearchMapperInterface
    {
        return new ServicePointSearchMapper(
            $this->getUtilEncodingService(),
            $this->createServicePointSearchDataMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ServicePointSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ServicePointSearchToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToStoreFacadeInterface
     */
    public function getStoreFacade(): ServicePointSearchToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ServicePointSearch\Dependency\Service\ServicePointSearchToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ServicePointSearchToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
