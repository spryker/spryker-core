<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage;

use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery;
use Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CategoryImage\CategoryImageConfig getConfig()
 */
class CategoryImageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    public const PROPEL_QUERY_CATEGORY_IMAGE = 'PROPEL_QUERY_CATEGORY_IMAGE';
    public const PROPEL_QUERY_CATEGORY_IMAGE_SET = 'PROPEL_QUERY_CATEGORY_IMAGE_SET';
    public const PROPEL_QUERY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE = 'PROPEL_QUERY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addLocaleFacade($container);
        $container = $this->addCategoryImagePropelQuery($container);
        $container = $this->addCategoryImageSetPropelQuery($container);
        $container = $this->addCategoryImageSetToCategoryImagePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new CategoryImageToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryImagePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CATEGORY_IMAGE] = function (Container $container) {
            return SpyCategoryImageQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryImageSetPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CATEGORY_IMAGE_SET] = function (Container $container) {
            return SpyCategoryImageSetQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryImageSetToCategoryImagePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE] = function (Container $container) {
            return SpyCategoryImageSetToCategoryImageQuery::create();
        };

        return $container;
    }
}
