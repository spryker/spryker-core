<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductCategoryFilter\FacetUpdater\FacetUpdaterByProductCategoryFilters;
use Spryker\Shared\ProductCategoryFilter\KeyBuilder\ProductCategoryFilterKeyBuilder;

class ProductCategoryFilterFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(ProductCategoryFilterDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Shared\ProductCategoryFilter\KeyBuilder\ProductCategoryFilterKeyBuilder
     */
    public function createProductCategoryFilterKeyBuilder()
    {
        return new ProductCategoryFilterKeyBuilder();
    }

    /**
     * @return \Spryker\Client\ProductCategoryFilter\FacetUpdater\FacetUpdaterInterface
     */
    public function createFacetUpdaterByProductCategoryFilters()
    {
        return new FacetUpdaterByProductCategoryFilters();
    }
}
