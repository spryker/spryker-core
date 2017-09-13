<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business;

use Spryker\Zed\CmsCollector\Business\Collector\Search\CmsVersionPageCollector as CmsVersionSearchPageCollector;
use Spryker\Zed\CmsCollector\Business\Collector\Storage\CmsVersionPageCollector as CmsVersionStoragePageCollector;
use Spryker\Zed\CmsCollector\Business\Extractor\DataExtractor;
use Spryker\Zed\CmsCollector\Business\Map\CmsDataPageMapBuilder;
use Spryker\Zed\CmsCollector\CmsCollectorDependencyProvider;
use Spryker\Zed\CmsCollector\Persistence\Collector\Search\Propel\CmsVersionPageCollectorQuery as CmsVersionPageCollectorSearchQuery;
use Spryker\Zed\CmsCollector\Persistence\Collector\Storage\Propel\CmsVersionPageCollectorQuery as CmsVersionPageCollectorStorageQuery;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsCollector\CmsCollectorConfig getConfig()
 */
class CmsCollectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CmsCollector\Business\Collector\Storage\CmsVersionPageCollector
     */
    public function createStorageCmsVersionPageCollector()
    {
        $cmsVersionPageCollector = new CmsVersionStoragePageCollector(
            $this->getUtilDataReaderService(),
            $this->createDataExtractor(),
            $this->getCmsFacade()
        );

        $cmsVersionPageCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $cmsVersionPageCollector->setQueryBuilder($this->createCmsVersionPageCollectorStorageQuery());

        return $cmsVersionPageCollector;
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Business\Collector\Search\CmsVersionPageCollector
     */
    public function createSearchCmsVersionPageCollector()
    {
        $cmsPageCollector = new CmsVersionSearchPageCollector(
            $this->getUtilDataReaderService(),
            $this->createCmsDataPageMapBuilder(),
            $this->getSearchFacade()
        );

        $cmsPageCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $cmsPageCollector->setQueryBuilder($this->createCmsVersionPageCollectorSearchQuery());

        return $cmsPageCollector;
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Business\Map\CmsDataPageMapBuilder
     */
    public function createCmsDataPageMapBuilder()
    {
        return new CmsDataPageMapBuilder(
            $this->createDataExtractor()
        );
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Business\Extractor\DataExtractor
     */
    public function createDataExtractor()
    {
        return new DataExtractor(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToSearchInterface
     */
    protected function getSearchFacade()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Dependency\Service\CmsCollectorToUtilEncodingInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Persistence\Collector\AbstractCmsVersionPageCollector
     */
    protected function createCmsVersionPageCollectorStorageQuery()
    {
        return new CmsVersionPageCollectorStorageQuery();
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Persistence\Collector\AbstractCmsVersionPageCollector
     */
    protected function createCmsVersionPageCollectorSearchQuery()
    {
        return new CmsVersionPageCollectorSearchQuery();
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface
     */
    protected function getCmsFacade()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::FACADE_CMS);
    }

}
