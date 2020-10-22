<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSearch\Business\DataMapper\MerchantSearchDataMapper;
use Spryker\Zed\MerchantSearch\Business\DataMapper\MerchantSearchDataMapperInterface;
use Spryker\Zed\MerchantSearch\Business\Deleter\MerchantSearchDeleter;
use Spryker\Zed\MerchantSearch\Business\Deleter\MerchantSearchDeleterInterface;
use Spryker\Zed\MerchantSearch\Business\Mapper\MerchantSearchMapper;
use Spryker\Zed\MerchantSearch\Business\Mapper\MerchantSearchMapperInterface;
use Spryker\Zed\MerchantSearch\Business\Writer\MerchantSearchWriter;
use Spryker\Zed\MerchantSearch\Business\Writer\MerchantSearchWriterInterface;
use Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface;
use Spryker\Zed\MerchantSearch\MerchantSearchDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSearch\MerchantSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchRepositoryInterface getRepository()
 */
class MerchantSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantSearchToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): MerchantSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\MerchantSearch\Business\Mapper\MerchantSearchMapperInterface
     */
    public function createMerchantSearchMapper(): MerchantSearchMapperInterface
    {
        return new MerchantSearchMapper(
            $this->getProvidedDependency(MerchantSearchDependencyProvider::SERVICE_UTIL_ENCODING),
            $this->createMerchantSearchDataMapper()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSearch\Business\Writer\MerchantSearchWriterInterface
     */
    public function createMerchantSearchWriter(): MerchantSearchWriterInterface
    {
        return new MerchantSearchWriter(
            $this->getMerchantFacade(),
            $this->getEventBehaviorFacade(),
            $this->createMerchantSearchMapper(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSearch\Business\Deleter\MerchantSearchDeleterInterface
     */
    public function createMerchantSearchDeleter(): MerchantSearchDeleterInterface
    {
        return new MerchantSearchDeleter(
            $this->getMerchantFacade(),
            $this->getEntityManager(),
            $this->getEventBehaviorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSearch\Business\DataMapper\MerchantSearchDataMapperInterface
     */
    public function createMerchantSearchDataMapper(): MerchantSearchDataMapperInterface
    {
        return new MerchantSearchDataMapper();
    }
}
