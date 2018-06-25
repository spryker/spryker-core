<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\Installer\ProductAlternativeProductLabelConnectorInstaller;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\Installer\ProductAlternativeProductLabelConnectorInstallerInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelWriter\ProductAlternativeProductLabelWriter;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence\ProductAlternativeProductLabelConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig getConfig()
 */
class ProductAlternativeProductLabelConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\Installer\ProductAlternativeProductLabelConnectorInstallerInterface
     */
    public function createProductAlternativeProductLabelConnectorInstaller(): ProductAlternativeProductLabelConnectorInstallerInterface
    {
        return new ProductAlternativeProductLabelConnectorInstaller($this->getConfig(), $this->getEntityManager(), $this->getProductLabelFacade());
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelInterface
     */
    public function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface
     */
    public function getProductAlternativeFacade()
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::FACADE_PRODUCT_ALTERNATIVE);
    }

    public function createProductAlternativeProductLabelWriter()
    {
        return new ProductAlternativeProductLabelWriter(
            $this->getProductFacade(),
            $this->getProductLabelFacade(),
            $this->getProductAlternativeFacade(),
            $this->getConfig()
        );
    }
}
