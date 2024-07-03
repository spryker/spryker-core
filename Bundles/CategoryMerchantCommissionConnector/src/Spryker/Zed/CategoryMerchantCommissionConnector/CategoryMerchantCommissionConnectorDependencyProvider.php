<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector;

use Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToCategoryFacadeBridge;
use Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToProductCategoryFacadeBridge;
use Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToProductFacadeBridge;
use Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToRuleEngineFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CategoryMerchantCommissionConnector\CategoryMerchantCommissionConnectorConfig getConfig()
 */
class CategoryMerchantCommissionConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CATEGORY = 'FACADE_CATEGORY';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_CATEGORY = 'FACADE_PRODUCT_CATEGORY';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_RULE_ENGINE = 'FACADE_RULE_ENGINE';

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
        $container = $this->addProductFacade($container);
        $container = $this->addRuleEngineFacade($container);

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
            return new CategoryMerchantCommissionConnectorToCategoryFacadeBridge(
                $container->getLocator()->category()->facade(),
            );
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
            return new CategoryMerchantCommissionConnectorToProductCategoryFacadeBridge(
                $container->getLocator()->productCategory()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new CategoryMerchantCommissionConnectorToProductFacadeBridge(
                $container->getLocator()->product()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRuleEngineFacade(Container $container): Container
    {
        $container->set(static::FACADE_RULE_ENGINE, function (Container $container) {
            return new CategoryMerchantCommissionConnectorToRuleEngineFacadeBridge(
                $container->getLocator()->ruleEngine()->facade(),
            );
        });

        return $container;
    }
}
