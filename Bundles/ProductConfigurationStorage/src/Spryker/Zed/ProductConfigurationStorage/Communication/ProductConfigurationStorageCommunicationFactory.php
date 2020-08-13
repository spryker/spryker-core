<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToProductConfigurationFacadeInterface;
use Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageConfig getConfig()
 * @method \Spryker\Zed\ProductConfigurationStorage\Business\ProductConfigurationStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepositoryInterface getRepository()
 */
class ProductConfigurationStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToProductConfigurationFacadeInterface
     */
    public function getProductConfigurationFacade(): ProductConfigurationStorageToProductConfigurationFacadeInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::FACADE_PRODUCT_CONFIGURATION);
    }
}
