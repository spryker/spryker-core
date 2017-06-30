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
        $cmsBlockCollector = new CmsBlockCollector(
            $this->getUtilDataReaderService(),
            $this->getCmsContentWidgetFacade()
        );

        $cmsBlockCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $cmsBlockCollector->setQueryBuilder($this->createCmsBlockCollectorStorageQuery());

        return $cmsBlockCollector;
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
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsContentWidgetInterface
     */
    protected function getCmsContentWidgetFacade()
    {
        return $this->getProvidedDependency(CmsBlockCollectorDependencyProvider::FACADE_CMS_CONTENT_WIDGET);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCollector\Persistence\Collector\Storage\Propel\CmsBlockCollectorQuery
     */
    protected function createCmsBlockCollectorStorageQuery()
    {
        return new CmsBlockCollectorQuery();
    }

}
