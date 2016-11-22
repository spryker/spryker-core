<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleWriter;
use Spryker\Zed\ProductBundle\ProductBundleDependencyProvider;

/**
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class ProductBundleBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleWriter
     */
    public function createProductBundleWriter()
    {
        return new ProductBundleWriter($this->getProductBundleFacade(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface
     */
    protected function getProductBundleFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_PRODUCT);
    }
}
