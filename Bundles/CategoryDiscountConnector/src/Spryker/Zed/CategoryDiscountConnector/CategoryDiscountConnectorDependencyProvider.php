<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector;

use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeBridge;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToDiscountFacadeBridge;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeBridge;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToProductCategoryFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CategoryDiscountConnector\CategoryDiscountConnectorConfig getConfig()
 */
class CategoryDiscountConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_DISCOUNT = 'FACADE_DISCOUNT';

    /**
     * @var string
     */
    public const FACADE_CATEGORY = 'FACADE_CATEGORY';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_CATEGORY = 'FACADE_PRODUCT_CATEGORY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCategoryFacade($container);
        $container = $this->addProductCategoryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addDiscountFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return new CategoryDiscountConnectorToCategoryFacadeBridge($container->getLocator()->category()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_CATEGORY, function (Container $container) {
            return new CategoryDiscountConnectorToProductCategoryFacadeBridge($container->getLocator()->productCategory()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new CategoryDiscountConnectorToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDiscountFacade(Container $container): Container
    {
        $container->set(static::FACADE_DISCOUNT, function (Container $container) {
            return new CategoryDiscountConnectorToDiscountFacadeBridge($container->getLocator()->discount()->facade());
        });

        return $container;
    }
}
