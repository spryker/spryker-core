<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToGlossaryBridge;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMailBridge;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMoneyBridge;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToSalesBridge;

class PayolutionDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_MAIL = 'mail facade';
    const FACADE_GLOSSARY = 'glossary facade';
    const FACADE_MONEY = 'money facade';
    const FACADE_SALES = 'sales facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new PayolutionToMoneyBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_MAIL] = function (Container $container) {
            return new PayolutionToMailBridge($container->getLocator()->mail()->facade());
        };

        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new PayolutionToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        $container[static::FACADE_SALES] = function (Container $container) {
            return new PayolutionToSalesBridge($container->getLocator()->sales()->facade());
        };

        return $container;
    }
}
