<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication;

use Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorDependencyProvider;
use Spryker\Zed\CmsBlockProductConnector\Communication\DataProvider\CmsBlockProductDataProvider;
use Spryker\Zed\CmsBlockProductConnector\Communication\Form\CmsBlockProductAbstractType;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
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
            $this->getQueryContainer(),
            $this->getProductAbstractQueryContainer(),
            $this->getLocaleFacade()
        );
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
}
