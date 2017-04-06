<?php

namespace Spryker\Zed\CmsCollector\Business;

use Spryker\Zed\CmsCollector\Business\Collector\Storage\CmsPageCollector;
use Spryker\Zed\CmsCollector\CmsCollectorDependencyProvider;
use Spryker\Zed\CmsCollector\Persistence\Collector\Propel\CmsPageCollectorQuery;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsCollector\CmsCollectorConfig getConfig()
 * @method \Spryker\Zed\CmsCollector\Persistence\CmsCollectorQueryContainer getQueryContainer()
 */
class CmsCollectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CmsPageCollector
     */
    public function createStorageCmsPageCollector()
    {
        $cmsPageCollector =  new CmsPageCollector(
            $this->getUtilDataReaderService()
        );

        $cmsPageCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $cmsPageCollector->setQueryBuilder($this->createCmsPageCollectorQuery());

        return $cmsPageCollector;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::SERVICE_DATA_READER);
    }

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

    protected function createCmsPageCollectorQuery()
    {
        return new CmsPageCollectorQuery();
    }

}
