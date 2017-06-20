<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Business;


use Spryker\Zed\CmsBlockCategoryConnector\Business\Collector\CmsBlockCategoryCollector;
use Spryker\Zed\CmsBlockCategoryConnector\Business\Model\CmsBlockCategoryWriter;
use Spryker\Zed\CmsBlockCategoryConnector\Business\Model\CmsBlockCategoryWriterInterface;
use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorDependencyProvider;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\TouchFacadeInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage\Propel\CmsBlockCategoryConnectorCollector;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

/**
 * @method CmsBlockCategoryConnectorQueryContainerInterface getQueryContainer()
 */
class CmsBlockCategoryConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CmsBlockCategoryWriterInterface
     */
    public function createCmsBlockCategoryWrite()
    {
        return new CmsBlockCategoryWriter(
            $this->getQueryContainer(),
            $this->getTouchFacade()
        );
    }

    /**
     * @return CmsBlockCategoryCollector
     */
    public function createStorageCmsBlockCategoryCollector()
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
     * @return TouchFacadeInterface
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
     * @return CmsBlockCategoryConnectorCollector
     */
    protected function createCmsBlockCategoryStorageQueryContainer()
    {
        return new CmsBlockCategoryConnectorCollector();
    }

}