<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business;

use Spryker\Zed\CmsBlockProductConnector\Business\Collector\CmsBlockProductCollector;
use Spryker\Zed\CmsBlockProductConnector\Business\Model\CmsBlockProductAbstractReader;
use Spryker\Zed\CmsBlockProductConnector\Business\Model\CmsBlockProductAbstractWriter;
use Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorDependencyProvider;
use Spryker\Zed\CmsBlockProductConnector\Persistence\Collector\Storage\Propel\CmsBlockProductConnectorCollectorQuery;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorRepositoryInterface getRepository()
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
     * @return \Spryker\Zed\CmsBlockProductConnector\Business\Model\CmsBlockProductAbstractReaderInterface
     */
    public function createCmsBlockProductAbstractReader()
    {
        return new CmsBlockProductAbstractReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Business\Collector\CmsBlockProductCollector
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
     * @return \Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToTouchInterface
     */
    public function getTouchFacade()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getDataReaderService()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Persistence\Collector\Storage\Propel\CmsBlockProductConnectorCollectorQuery
     */
    protected function createCmsBlockProductStorageQueryContainer()
    {
        return new CmsBlockProductConnectorCollectorQuery();
    }
}
