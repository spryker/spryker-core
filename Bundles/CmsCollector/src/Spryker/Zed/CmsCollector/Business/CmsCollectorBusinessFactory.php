<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business;

use Spryker\Zed\CmsCollector\Business\Collector\Storage\CmsVersionPageCollector as CmsVersionStoragePageCollector;
use Spryker\Zed\CmsCollector\Business\Collector\Search\CmsVersionPageCollector as CmsVersionSearchPageCollector;
use Spryker\Zed\CmsCollector\CmsCollectorDependencyProvider;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCollectorInterface;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToSearchInterface;
use Spryker\Zed\CmsCollector\Persistence\Collector\AbstractCmsVersionPageCollector;
use Spryker\Zed\CmsCollector\Persistence\Collector\Storage\Propel\CmsVersionPageCollectorQuery as CmsVersionPageCollectorStorageQuery;
use Spryker\Zed\CmsCollector\Persistence\Collector\Search\Propel\CmsVersionPageCollectorQuery as CmsVersionPageCollectorSearchQuery;
use Spryker\Zed\CmsCollector\Business\Map\CmsDataPageMapBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsCollector\CmsCollectorConfig getConfig()
 */
class CmsCollectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CmsVersionStoragePageCollector
     */
    public function createStorageCmsVersionPageCollector()
    {
        $cmsVersionPageCollector =  new CmsVersionStoragePageCollector(
            $this->getUtilDataReaderService()
        );

        $cmsVersionPageCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $cmsVersionPageCollector->setQueryBuilder($this->createCmsVersionPageCollectorStorageQuery());

        return $cmsVersionPageCollector;
    }

    /**
     * @return CmsVersionSearchPageCollector
     */
    public function createSearchCmsVersionPageCollector()
    {
        $cmsPageCollector = new CmsVersionSearchPageCollector(
            $this->getUtilDataReaderService(),
            $this->getProvidedDependency(CmsCollectorDependencyProvider::PLUGIN_CMS_PAGE_DATA_PAGE_MAP),
            $this->getSearchFacade()
        );

        $cmsPageCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $cmsPageCollector->setQueryBuilder($this->createCmsVersionPageCollectorSearchQuery());

        return $cmsPageCollector;
    }

    /**
     * @return CmsDataPageMapBuilder
     */
    public function createCmsDataPageMapBuilder()
    {
        return new CmsDataPageMapBuilder();
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return CmsCollectorToCollectorInterface
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
     * @return CmsCollectorToSearchInterface
     */
    protected function getSearchFacade()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return AbstractCmsVersionPageCollector
     */
    protected function createCmsVersionPageCollectorStorageQuery()
    {
        return new CmsVersionPageCollectorStorageQuery();
    }

    /**
     * @return AbstractCmsVersionPageCollector
     */
    protected function createCmsVersionPageCollectorSearchQuery()
    {
        return new CmsVersionPageCollectorSearchQuery();
    }
}
