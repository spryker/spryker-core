<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Locale;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Locale\Dependency\Client\LocaleToStoreClientInterface;

/**
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 */
class LocaleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface
     */
    public function getLocalePlugin(): LocalePluginInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::PLUGIN_LOCALE);
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Yves\Locale\Dependency\Client\LocaleToStoreClientInterface
     */
    public function getStoreClient(): LocaleToStoreClientInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::CLIENT_STORE);
    }
}
