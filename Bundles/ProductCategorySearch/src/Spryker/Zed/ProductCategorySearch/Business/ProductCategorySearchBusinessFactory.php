<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageDataExpander;
use Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageDataExpanderInterface;
use Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageLoadExpander;
use Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageLoadExpanderInterface;
use Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageMapCategoryExpander;
use Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageMapCategoryExpanderInterface;

/**
 * @method \Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductCategorySearch\ProductCategorySearchConfig getConfig()
 */
class ProductCategorySearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageLoadExpanderInterface
     */
    public function createProductPageLoadExpander(): ProductPageLoadExpanderInterface
    {
        return new ProductPageLoadExpander(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageDataExpanderInterface
     */
    public function createProductPageDataExpander(): ProductPageDataExpanderInterface
    {
        return new ProductPageDataExpander(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategorySearch\Business\Expander\ProductPageMapCategoryExpanderInterface
     */
    public function createProductPageMapCategoryExpander(): ProductPageMapCategoryExpanderInterface
    {
        return new ProductPageMapCategoryExpander();
    }
}
