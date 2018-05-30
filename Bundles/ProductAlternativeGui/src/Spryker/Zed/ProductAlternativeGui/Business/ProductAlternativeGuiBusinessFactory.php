<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAlternativeGui\Business\Product\ProductSuggester;
use Spryker\Zed\ProductAlternativeGui\Business\Product\ProductSuggesterInterface;
use Spryker\Zed\ProductAlternativeGui\Business\ProductAlternative\ProductAlternativeCreator;
use Spryker\Zed\ProductAlternativeGui\Business\ProductAlternative\ProductAlternativeCreatorInterface;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface;
use Spryker\Zed\ProductAlternativeGui\ProductAlternativeGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAlternativeGui\ProductAlternativeGuiConfig getConfig()
 */
class ProductAlternativeGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductAlternativeGui\Business\Product\ProductSuggesterInterface
     */
    public function createProductSuggester(): ProductSuggesterInterface
    {
        return new ProductSuggester(
            $this->getProductFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeGui\Business\ProductAlternative\ProductAlternativeCreatorInterface
     */
    public function createProductAlternativeCreator(): ProductAlternativeCreatorInterface
    {
        return new ProductAlternativeCreator(
            $this->getProductAlternativeFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductAlternativeGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductAlternativeGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface
     */
    public function getProductAlternativeFacade(): ProductAlternativeGuiToProductAlternativeFacadeInterface
    {
        return $this->getProvidedDependency(ProductAlternativeGuiDependencyProvider::FACADE_PRODUCT_ALTERNATIVE);
    }
}
