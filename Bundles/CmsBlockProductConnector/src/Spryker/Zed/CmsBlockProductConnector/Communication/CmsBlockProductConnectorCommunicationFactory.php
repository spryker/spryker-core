<?php

namespace Spryker\Zed\CmsBlockProductConnector\Communication;

use Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorDependencyProvider;
use Spryker\Zed\CmsBlockProductConnector\Communication\DataProvider\CmsBlockProductDataProvider;
use Spryker\Zed\CmsBlockProductConnector\Communication\Form\CmsBlockProductAbstractType;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\LocaleFacadeInterface;
use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\ProductAbstractQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface;

/**
 * @method CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 */
class CmsBlockProductConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return CmsBlockProductAbstractType
     */
    public function createCmsBlockProductAbstractType()
    {
        return new CmsBlockProductAbstractType();
    }

    /**
     * @return CmsBlockProductDataProvider
     */
    public function createCmsBlockProductDataProvider()
    {
        return new CmsBlockProductDataProvider(
            $this->getQueryContainer(),
            $this->getProductAbstractQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return ProductAbstractQueryContainerInterface
     */
    protected function getProductAbstractQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT_ABSTRACT);
    }

    /**
     * @return LocaleFacadeInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::FACADE_LOCALE);
    }

}