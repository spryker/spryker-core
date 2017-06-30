<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business;

use Spryker\Zed\CmsBlockCategoryConnector\Business\Collector\CmsBlockCategoryCollector;
use Spryker\Zed\CmsBlockCategoryConnector\Business\Model\CmsBlockCategoryReader;
use Spryker\Zed\CmsBlockCategoryConnector\Business\Model\CmsBlockCategoryWriter;
use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorDependencyProvider;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage\Propel\CmsBlockCategoryConnectorCollector;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig getConfig()
 */
class CmsBlockCategoryConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Business\Model\CmsBlockCategoryWriterInterface
     */
    public function createCmsBlockCategoryWrite()
    {
        return new CmsBlockCategoryWriter(
            $this->getQueryContainer(),
            $this->getTouchFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Business\Model\CmsBlockCategoryReaderInterface
     */
    public function createCmsBlockCategoryReader()
    {
        return new CmsBlockCategoryReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Business\Collector\CmsBlockCategoryCollector
     */
    public function createStorageCmsBlockCategoryConnectorCollector()
    {
        $cmsBlockCategoryCollector = new CmsBlockCategoryCollector(
            $this->getUtilDataReaderService()
        );

        $cmsBlockCategoryCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $cmsBlockCategoryCollector->setQueryBuilder($this->createCmsBlockCategoryStorageQueryContainer());

        return $cmsBlockCategoryCollector;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(CmsBlockCategoryConnectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCollector\Dependency\Facade\CmsBlockCollectorToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(CmsBlockCategoryConnectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(CmsBlockCategoryConnectorDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockCategoryConnectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage\Propel\CmsBlockCategoryConnectorCollector
     */
    protected function createCmsBlockCategoryStorageQueryContainer()
    {
        return new CmsBlockCategoryConnectorCollector();
    }

}
