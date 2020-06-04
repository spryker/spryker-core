<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesReturnSearch\Business\DataMapper\ReturnReasonSearchDataMapper;
use Spryker\Zed\SalesReturnSearch\Business\DataMapper\ReturnReasonSearchDataMapperInterface;
use Spryker\Zed\SalesReturnSearch\Business\Deleter\ReturnReasonSearchDeleter;
use Spryker\Zed\SalesReturnSearch\Business\Deleter\ReturnReasonSearchDeleterInterface;
use Spryker\Zed\SalesReturnSearch\Business\Mapper\ReturnReasonSearchMapper;
use Spryker\Zed\SalesReturnSearch\Business\Mapper\ReturnReasonSearchMapperInterface;
use Spryker\Zed\SalesReturnSearch\Business\Reader\GlossaryReader;
use Spryker\Zed\SalesReturnSearch\Business\Reader\GlossaryReaderInterface;
use Spryker\Zed\SalesReturnSearch\Business\Writer\ReturnReasonSearchWriter;
use Spryker\Zed\SalesReturnSearch\Business\Writer\ReturnReasonSearchWriterInterface;
use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToGlossaryFacadeInterface;
use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToLocaleFacadeInterface;
use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnSearch\Dependency\Service\SalesReturnSearchToUtilEncodingServiceInterface;
use Spryker\Zed\SalesReturnSearch\SalesReturnSearchDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReturnSearch\SalesReturnSearchConfig getConfig()
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchRepositoryInterface getRepository()
 */
class SalesReturnSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesReturnSearch\Business\Writer\ReturnReasonSearchWriterInterface
     */
    public function createReturnReasonSearchWriter(): ReturnReasonSearchWriterInterface
    {
        return new ReturnReasonSearchWriter(
            $this->getSalesReturnFacade(),
            $this->getEventBehaviorFacade(),
            $this->createGlossaryReader(),
            $this->createReturnReasonSearchMapper(),
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnSearch\Business\Deleter\ReturnReasonSearchDeleterInterface
     */
    public function createReturnReasonSearchDeleter(): ReturnReasonSearchDeleterInterface
    {
        return new ReturnReasonSearchDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnSearch\Business\Reader\GlossaryReaderInterface
     */
    public function createGlossaryReader(): GlossaryReaderInterface
    {
        return new GlossaryReader(
            $this->getGlossaryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnSearch\Business\DataMapper\ReturnReasonSearchDataMapperInterface
     */
    public function createReturnReasonSearchDataMapper(): ReturnReasonSearchDataMapperInterface
    {
        return new ReturnReasonSearchDataMapper();
    }

    /**
     * @return \Spryker\Zed\SalesReturnSearch\Business\Mapper\ReturnReasonSearchMapperInterface
     */
    public function createReturnReasonSearchMapper(): ReturnReasonSearchMapperInterface
    {
        return new ReturnReasonSearchMapper(
            $this->getUtilEncodingService(),
            $this->createReturnReasonSearchDataMapper()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): SalesReturnSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToSalesReturnFacadeInterface
     */
    public function getSalesReturnFacade(): SalesReturnSearchToSalesReturnFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::FACADE_SALES_RETURN);
    }

    /**
     * @return \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): SalesReturnSearchToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToLocaleFacadeInterface
     */
    public function getLocaleFacade(): SalesReturnSearchToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\SalesReturnSearch\Dependency\Service\SalesReturnSearchToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): SalesReturnSearchToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
