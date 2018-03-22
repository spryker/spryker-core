<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductSetGui\ProductSetGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetGui\ProductSetGuiConfig getConfig()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class ProductSetGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductSetInterface
     */
    public function getProductSetQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}
