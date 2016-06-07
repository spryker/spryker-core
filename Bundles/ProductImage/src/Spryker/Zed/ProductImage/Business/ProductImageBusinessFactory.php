<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductImage\ProductImageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer getQueryContainer()
 */
class ProductImageBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductImageDependencyProvider::FACADE_PRODUCT);
    }

}
