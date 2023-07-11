<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Communication;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Locale\Dependency\Facade\LocaleToStoreFacadeInterface;
use Spryker\Zed\Locale\LocaleDependencyProvider;

/**
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Business\LocaleFacadeInterface getFacade()
 * @method \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface getRepository()
 * @method \Spryker\Zed\Locale\Persistence\LocaleEntityManagerInterface getEntityManager()
 */
class LocaleCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface
     */
    public function getLocalePlugin(): LocalePluginInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::PLUGIN_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Locale\Dependency\Facade\LocaleToStoreFacadeInterface
     */
    public function getStoreFacade(): LocaleToStoreFacadeInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::FACADE_STORE);
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
}
