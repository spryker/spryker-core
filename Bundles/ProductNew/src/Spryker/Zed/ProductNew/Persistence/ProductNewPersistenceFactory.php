<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductNew\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductNew\ProductNewDependencyProvider;

/**
 * @method \Spryker\Zed\ProductNew\ProductNewConfig getConfig()
 * @method \Spryker\Zed\ProductNew\Persistence\ProductNewQueryContainer getQueryContainer()
 */
class ProductNewPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\ProductNew\Dependency\QueryContainer\ProductNewToProductLabelInterface
     */
    public function getProductLabelQueryContainer()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::QUERY_CONTAINER_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\ProductNew\Dependency\QueryContainer\ProductNewToProductInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductNewDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

}
