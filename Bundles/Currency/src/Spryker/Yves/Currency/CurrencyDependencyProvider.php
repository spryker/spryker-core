<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency;

use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationBridge;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Currency\Dependency\Client\CurrencyToSessionBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CurrencyDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @var string
     */
    public const STORE = 'store';

    /**
     * @var string
     */
    public const INTERNATIONALIZATION = 'internationalization';

    /**
     * @var string
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addStore($container);
        $container = $this->addInternationalization($container);
        $container = $this->addSessionClient($container);

        return $container;
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container->set(static::STORE, function () {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addInternationalization(Container $container): Container
    {
        $container->set(static::INTERNATIONALIZATION, function () {
            return new CurrencyToInternationalizationBridge();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return new CurrencyToSessionBridge($container->getLocator()->session()->client());
        });

        return $container;
    }
}
