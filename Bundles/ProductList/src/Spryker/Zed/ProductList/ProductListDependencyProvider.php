<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList;

use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ProductListDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_PRODUCT_CATEGORY_QUERY = 'PROPEL_PRODUCT_CATEGORY_QUERY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addProductCategoryPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_PRODUCT_CATEGORY_QUERY] = function (Container $container) {
            return SpyProductCategoryQuery::create();
        };

        return $container;
    }
}
