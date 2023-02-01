<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi;

use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeBridge;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryImageFacadeBridge;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToLocaleFacadeBridge;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToStoreFacadeBridge;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;

/**
 * @method \Spryker\Glue\CategoriesBackendApi\CategoriesBackendApiConfig getConfig()
 */
class CategoriesBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CATEGORY = 'FACADE_CATEGORY';

    /**
     * @var string
     */
    public const FACADE_CATEGORY_IMAGE = 'FACADE_CATEGORY_IMAGE';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addCategoryFacade($container);
        $container = $this->addCategoryImageFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return new CategoriesBackendApiToCategoryFacadeBridge($container->getLocator()->category()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addCategoryImageFacade(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY_IMAGE, function (Container $container) {
            return new CategoriesBackendApiToCategoryImageFacadeBridge($container->getLocator()->categoryImage()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new CategoriesBackendApiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new CategoriesBackendApiToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }
}
