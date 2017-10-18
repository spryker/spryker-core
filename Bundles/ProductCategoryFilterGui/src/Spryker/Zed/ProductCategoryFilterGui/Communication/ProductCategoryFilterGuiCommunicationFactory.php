<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductCategoryFilterGui\ProductCategoryFilterGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\ProductCategoryFilterGuiConfig getConfig()
 */
class ProductCategoryFilterGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterInterface
     */
    public function getProductCategoryFilterFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::FACADE_PRODUCT_CATEGORY_FILTER);
    }
}
