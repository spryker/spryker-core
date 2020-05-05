<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\Installer\ProductDiscontinuedProductLabelConnectorInstaller;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\Installer\ProductDiscontinuedProductLabelConnectorInstallerInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelReader\ProductAbstractRelationReader;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelReader\ProductAbstractRelationReaderInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelWriter\ProductDiscontinuedProductLabelWriter;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelWriter\ProductDiscontinuedProductLabelWriterInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToLocaleFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig getConfig()
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorRepositoryInterface getRepository()
 */
class ProductDiscontinuedProductLabelConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\Installer\ProductDiscontinuedProductLabelConnectorInstallerInterface
     */
    public function createProductDiscontinuedProductLabelConnectorInstaller(): ProductDiscontinuedProductLabelConnectorInstallerInterface
    {
        return new ProductDiscontinuedProductLabelConnectorInstaller(
            $this->getConfig(),
            $this->getProductLabelFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelWriter\ProductDiscontinuedProductLabelWriterInterface
     */
    public function createProductDiscontinuedProductLabelWriter(): ProductDiscontinuedProductLabelWriterInterface
    {
        return new ProductDiscontinuedProductLabelWriter(
            $this->getProductFacade(),
            $this->getProductLabelFacade(),
            $this->getProductDiscontinuedFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelReader\ProductAbstractRelationReaderInterface
     */
    public function createProductAbstractRelationReader(): ProductAbstractRelationReaderInterface
    {
        return new ProductAbstractRelationReader(
            $this->getProductLabelFacade(),
            $this->getConfig(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface
     */
    public function getProductLabelFacade(): ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductInterface
     */
    public function getProductFacade(): ProductDiscontinuedProductLabelConnectorToProductInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinuedFacade(): ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::FACADE_PRODUCT_DISCONTINUED);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductDiscontinuedProductLabelConnectorToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::FACADE_LOCALE);
    }
}
