<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;
use SprykerFeature\Zed\Sales\Business\SalesFacade;

class PayolutionDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_SALES = 'facade sales';

    /**
     * @param Container $container
     *
     * @return SalesFacade
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        return $container[self::FACADE_SALES] = $container->getLocator()->sales()->facade();
    }

}
