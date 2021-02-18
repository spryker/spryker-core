<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\TaxMerchantPortalGui\Dependency\Facade\TaxMerchantPortalGuiToTaxFacadeBridge;

class TaxMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_TAX = 'FACADE_TAX';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addTaxFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTaxFacade(Container $container): Container
    {
        $container->set(static::FACADE_TAX, function (Container $container) {
            return new TaxMerchantPortalGuiToTaxFacadeBridge($container->getLocator()->tax()->facade());
        });

        return $container;
    }
}
