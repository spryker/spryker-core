<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDynamicEntityConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDynamicEntityConnector\Business\Updater\UrlUpdater;
use Spryker\Zed\ProductDynamicEntityConnector\Business\Updater\UrlUpdaterInterface;
use Spryker\Zed\ProductDynamicEntityConnector\Dependency\Facade\ProductDynamicEntityConnectorToProductFacadeInterface;
use Spryker\Zed\ProductDynamicEntityConnector\ProductDynamicEntityConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDynamicEntityConnector\ProductDynamicEntityConnectorConfig getConfig()
 */
class ProductDynamicEntityConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductDynamicEntityConnector\Business\Updater\UrlUpdaterInterface
     */
    public function createUrlUpdater(): UrlUpdaterInterface
    {
        return new UrlUpdater(
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductDynamicEntityConnector\Dependency\Facade\ProductDynamicEntityConnectorToProductFacadeInterface
     */
    public function getProductFacade(): ProductDynamicEntityConnectorToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductDynamicEntityConnectorDependencyProvider::FACADE_PRODUCT);
    }
}
