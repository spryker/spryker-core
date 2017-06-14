<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCollector\Business;

use Spryker\Zed\CmsBlockCollector\Business\Collector\Storage\CmsBlockCollector;
use Spryker\Zed\CmsBlockCollector\CmsBlockCollectorDependencyProvider;
use Spryker\Zed\CmsBlockCollector\Persistence\Collector\Storage\Propel\CmsBlockCollectorQuery;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;


/**
 * @method \Spryker\Zed\CmsBlockCollector\CmsBlockCollectorConfig getConfig()
 */
class CmsBlockCollectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CmsBlockCollector\Business\Collector\Storage\CmsBlockCollector
     */
    public function createStorageCmsBlockCollector()
    {
        $cmsVersionPageCollector = new CmsBlockCollector(
            $this->getUtilDataReaderService()
        );

        $cmsVersionPageCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $cmsVersionPageCollector->setQueryBuilder($this->createCmsBlockCollectorStorageQuery());

        return $cmsVersionPageCollector;
    }

    /**
     * @return \Spryker\Zed\CmsBlockCollector\Dependency\Facade\CmsBlockCollectorToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(CmsBlockCollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(CmsBlockCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCollector\Dependency\Service\CmsBlockCollectorToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(CmsBlockCollectorDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCollector\Persistence\Collector\AbstractCmsBlockCollector
     */
    protected function createCmsBlockCollectorStorageQuery()
    {
        return new CmsBlockCollectorQuery();
    }

}
