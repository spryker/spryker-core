<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication;

use Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorDependencyProvider;
use Spryker\Zed\CmsBlockProductConnector\Communication\DataProvider\CmsBlockProductDataProvider;
use Spryker\Zed\CmsBlockProductConnector\Communication\Form\CmsBlockProductAbstractType;
use Spryker\Zed\CmsBlockProductConnector\Communication\Formatter\ProductLabelFormatter;
use Spryker\Zed\CmsBlockProductConnector\Communication\Formatter\ProductLabelFormatterInterface;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToProductFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorRepositoryInterface getRepository()
 */
class CmsBlockProductConnectorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Communication\Form\CmsBlockProductAbstractType
     */
    public function createCmsBlockProductAbstractType()
    {
        return new CmsBlockProductAbstractType();
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Communication\DataProvider\CmsBlockProductDataProvider
     */
    public function createCmsBlockProductDataProvider()
    {
        return new CmsBlockProductDataProvider(
            $this->getLocaleFacade(),
            $this->getRepository(),
            $this->createProductLabelFormatter()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Communication\Formatter\ProductLabelFormatterInterface
     */
    public function createProductLabelFormatter(): ProductLabelFormatterInterface
    {
        return new ProductLabelFormatter();
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerInterface
     */
    protected function getProductAbstractQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToProductFacadeInterface
     */
    public function getProductFacade(): CmsBlockProductConnectorToProductFacadeInterface
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::FACADE_PRODUCT);
    }
}
