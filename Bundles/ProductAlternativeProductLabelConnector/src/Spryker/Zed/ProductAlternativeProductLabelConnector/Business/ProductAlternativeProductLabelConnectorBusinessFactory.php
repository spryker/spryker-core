<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\Installer\ProductAlternativeProductLabelConnectorInstaller;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\Installer\ProductAlternativeProductLabelConnectorInstallerInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelReader\ProductAbstractRelationReader;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelReader\ProductAbstractRelationReaderInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelWriter\ProductAlternativeProductLabelWriter;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelWriter\ProductAlternativeProductLabelWriterInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToAvailabilityFacadeBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToLocaleFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig getConfig()
 */
class ProductAlternativeProductLabelConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\Installer\ProductAlternativeProductLabelConnectorInstallerInterface
     */
    public function createProductAlternativeProductLabelConnectorInstaller(): ProductAlternativeProductLabelConnectorInstallerInterface
    {
        return new ProductAlternativeProductLabelConnectorInstaller(
            $this->getConfig(),
            $this->getProductLabelFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelWriter\ProductAlternativeProductLabelWriterInterface
     */
    public function createProductAlternativeProductLabelWriter(): ProductAlternativeProductLabelWriterInterface
    {
        return new ProductAlternativeProductLabelWriter(
            $this->getProductFacade(),
            $this->getProductLabelFacade(),
            $this->getProductAlternativeFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelReader\ProductAbstractRelationReaderInterface
     */
    public function createProductAbstractRelationReader(): ProductAbstractRelationReaderInterface
    {
        return new ProductAbstractRelationReader(
            $this->getProductFacade(),
            $this->getProductLabelFacade(),
            $this->getProductAlternativeFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface
     */
    public function getProductLabelFacade(): ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface
     */
    public function getProductFacade(): ProductAlternativeProductLabelConnectorToProductInterface
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface
     */
    public function getProductAlternativeFacade(): ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::FACADE_PRODUCT_ALTERNATIVE);
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductAlternativeProductLabelConnectorToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::FACADE_LOCALE);
    }
}
