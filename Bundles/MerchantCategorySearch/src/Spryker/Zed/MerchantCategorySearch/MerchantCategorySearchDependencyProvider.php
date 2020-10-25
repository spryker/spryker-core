<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategorySearch;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantCategorySearch\Dependency\Facade\MerchantCategorySearchToMerchantCategoryFacadeBridge;

class MerchantCategorySearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_CATEGORY = 'FACADE_MERCHANT_CATEGORY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMerchantCategoryFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_CATEGORY, function (Container $container) {
            return new MerchantCategorySearchToMerchantCategoryFacadeBridge($container->getLocator()->merchantCategory()->facade());
        });

        return $container;
    }
}
