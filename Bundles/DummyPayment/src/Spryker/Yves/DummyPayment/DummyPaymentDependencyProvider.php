<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyPayment;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class DummyPaymentDependencyProvider extends AbstractBundleDependencyProvider
{

    const CURRENCY_MANAGER = 'currency manager';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCurrencyManager($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCurrencyManager(Container $container)
    {
        $container[self::CURRENCY_MANAGER] = function () {
            return CurrencyManager::getInstance();
        };

        return $container;
    }

}
