<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business;

use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Zed\CmsBlockProductConnector\Business\Collector\CmsBlockProductCollector;
use Spryker\Zed\CmsBlockProductConnector\Business\Model\CmsBlockProductAbstractWriter;
use Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorDependencyProvider;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToCollectorFacadeInterface;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToTouchFacadeInterface;
use Spryker\Zed\CmsBlockProductConnector\Persistence\Collector\Storage\AbstractCmsBlockProductConnectorCollector;
use Spryker\Zed\CmsBlockProductConnector\Persistence\Collector\Storage\Propel\CmsBlockProductConnectorCollectorQuery;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorConfig getConfig()
 */
class CmsBlockProductConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Business\Model\CmsBlockProductAbstractWriterInterface
     */
    public function createCmsBlockProductAbstractWriter()
    {
        return new CmsBlockProductAbstractWriter(
            $this->getQueryContainer(),
            $this->getTouchFacade()
        );
    }

    /**
     * @return CmsBlockProductCollector
     */
    public function createStorageCmsBlockProductConnectorCollector()
    {
        $collector = new CmsBlockProductCollector(
            $this->getDataReaderService()
        );

        $collector->setTouchQueryContainer($this->getTouchQueryContainer());
        $collector->setQueryBuilder($this->createCmsBlockProductStorageQueryContainer());

        return $collector;
    }

    /**
     * @return CmsBlockProductConnectorToCollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return CmsBlockProductConnectorToTouchFacadeInterface
     */
    public function getTouchFacade()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return UtilDataReaderServiceInterface
     */
    protected function getDataReaderService()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return AbstractCmsBlockProductConnectorCollector
     */
    protected function createCmsBlockProductStorageQueryContainer()
    {
        return new CmsBlockProductConnectorCollectorQuery();
    }

}
