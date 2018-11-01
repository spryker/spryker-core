<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSearchConfigStorage\Business\Storage\ProductSearchConfigStorageWriter;
use Spryker\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageConfig getConfig()
 * @method \Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStorageQueryContainerInterface getQueryContainer()
 */
class ProductSearchConfigStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductSearchConfigStorage\Business\Storage\ProductSearchConfigStorageWriterInterface
     */
    public function createProductSearchConfigStorageWriter()
    {
        return new ProductSearchConfigStorageWriter(
            $this->getQueryContainer(),
            $this->getProductSearchFacade(),
            $this->getProductSearchConfig(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\ProductSearchConfig
     */
    protected function getProductSearchConfig()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::CONFIG_PRODUCT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToProductSearchFacadeInterface
     */
    protected function getProductSearchFacade()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::FACADE_PRODUCT_SEARCH);
    }
}
