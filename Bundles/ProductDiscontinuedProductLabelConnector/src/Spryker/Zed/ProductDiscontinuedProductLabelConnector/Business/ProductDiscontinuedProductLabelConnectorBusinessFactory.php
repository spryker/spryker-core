<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\Installer\ProductDiscontinuedProductLabelConnectorInstaller;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\Installer\ProductDiscontinuedProductLabelConnectorInstallerInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelWriter\ProductDiscontinuedProductLabelWriter;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig getConfig()
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
            $this->getGlossaryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelWriter\ProductDiscontinuedProductLabelWriterInterface
     */
    public function createProductDiscontinuedProductLabelWriter()
    {
        return new ProductDiscontinuedProductLabelWriter(
            $this->getProductFacade(),
            $this->getProductLabelFacade(),
            $this->getProductDiscontinuedFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelInterface
     */
    public function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinuedFacade()
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::FACADE_PRODUCT_DISCONTINUED);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToGlossaryFacadeInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToLocaleFacadeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::FACADE_LOCALE);
    }
}
