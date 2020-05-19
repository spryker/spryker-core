<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesReturnPageSearch\Business\DataMapper\ReturnReasonPageSearchDataMapper;
use Spryker\Zed\SalesReturnPageSearch\Business\DataMapper\ReturnReasonPageSearchDataMapperInterface;
use Spryker\Zed\SalesReturnPageSearch\Business\Deleter\ReturnReasonSearchDeleter;
use Spryker\Zed\SalesReturnPageSearch\Business\Deleter\ReturnReasonSearchDeleterInterface;
use Spryker\Zed\SalesReturnPageSearch\Business\Mapper\ReturnReasonPageSearchMapper;
use Spryker\Zed\SalesReturnPageSearch\Business\Mapper\ReturnReasonPageSearchMapperInterface;
use Spryker\Zed\SalesReturnPageSearch\Business\Reader\GlossaryReader;
use Spryker\Zed\SalesReturnPageSearch\Business\Reader\GlossaryReaderInterface;
use Spryker\Zed\SalesReturnPageSearch\Business\Writer\ReturnReasonSearchWriter;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToGlossaryFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToLocaleFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Service\SalesReturnPageSearchToUtilEncodingServiceInterface;
use Spryker\Zed\SalesReturnPageSearch\SalesReturnPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\SalesReturnPageSearchConfig getConfig()
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface getRepository()
 */
class SalesReturnPageSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Business\Writer\ReturnReasonSearchWriter
     */
    public function createReturnReasonSearchWriter(): ReturnReasonSearchWriter
    {
        return new ReturnReasonSearchWriter(
            $this->getSalesReturnFacade(),
            $this->getEventBehaviorFacade(),
            $this->createGlossaryReader(),
            $this->createReturnReasonPageSearchMapper(),
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Business\Deleter\ReturnReasonSearchDeleterInterface
     */
    public function createReturnReasonSearchDeleter(): ReturnReasonSearchDeleterInterface
    {
        return new ReturnReasonSearchDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Business\Reader\GlossaryReaderInterface
     */
    public function createGlossaryReader(): GlossaryReaderInterface
    {
        return new GlossaryReader(
            $this->getGlossaryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Business\DataMapper\ReturnReasonPageSearchDataMapperInterface
     */
    public function createReturnReasonPageSearchDataMapper(): ReturnReasonPageSearchDataMapperInterface
    {
        return new ReturnReasonPageSearchDataMapper();
    }

    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Business\Mapper\ReturnReasonPageSearchMapperInterface
     */
    public function createReturnReasonPageSearchMapper(): ReturnReasonPageSearchMapperInterface
    {
        return new ReturnReasonPageSearchMapper(
            $this->getUtilEncodingService(),
            $this->createReturnReasonPageSearchDataMapper()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): SalesReturnPageSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnPageSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToSalesReturnFacadeInterface
     */
    public function getSalesReturnFacade(): SalesReturnPageSearchToSalesReturnFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnPageSearchDependencyProvider::FACADE_SALES_RETURN);
    }

    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): SalesReturnPageSearchToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnPageSearchDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToLocaleFacadeInterface
     */
    public function getLocaleFacade(): SalesReturnPageSearchToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnPageSearchDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Dependency\Service\SalesReturnPageSearchToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): SalesReturnPageSearchToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SalesReturnPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
