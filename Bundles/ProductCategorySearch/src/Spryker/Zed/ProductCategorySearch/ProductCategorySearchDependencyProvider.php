<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch;

use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ProductCategorySearch\ProductCategorySearchConfig getConfig()
 */
class ProductCategorySearchDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_CATEGORY = 'PROPEL_QUERY_PRODUCT_CATEGORY';

    /**
     * @var string
     */
    public const PROPEL_QUERY_CATEGORY_NODE = 'PROPEL_QUERY_CATEGORY_NODE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_CATEGORY_ATTRIBUTE = 'PROPEL_QUERY_CATEGORY_ATTRIBUTE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addProductCategoryPropelQuery($container);
        $container = $this->addCategoryNodePropelQuery($container);
        $container = $this->addCategoryAttributePropelQuery($container);

        return $container;
    }

    /**
     * @module ProductCategory
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_CATEGORY, $container->factory(function () {
            return SpyProductCategoryQuery::create();
        }));

        return $container;
    }

    /**
     * @module Category
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryNodePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CATEGORY_NODE, $container->factory(function () {
            return SpyCategoryNodeQuery::create();
        }));

        return $container;
    }

    /**
     * @module Category
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryAttributePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CATEGORY_ATTRIBUTE, $container->factory(function () {
            return SpyCategoryAttributeQuery::create();
        }));

        return $container;
    }
}
