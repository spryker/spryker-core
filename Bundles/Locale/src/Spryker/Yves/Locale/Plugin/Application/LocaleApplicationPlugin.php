<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Locale\Plugin\Application;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\Config\Application\Environment as ApplicationEnvironment;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Locale\LocaleFactory getFactory()
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 */
class LocaleApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @uses \Spryker\Client\Locale\LocaleDependencyProvider::SERVICE_LOCALE
     *
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * Added for BC reason only.
     *
     * @var string
     */
    protected const BC_FEATURE_FLAG_LOCALE_LISTENER = 'BC_FEATURE_FLAG_LOCALE_LISTENER';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addLocale($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addLocale(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::BC_FEATURE_FLAG_LOCALE_LISTENER, false);
        $container->set(static::SERVICE_LOCALE, function (ContainerInterface $container) {
            $localeName = $this->getLocaleTransfer($container)->getLocaleNameOrFail();
            $this->setStoreCurrentLocale($localeName);
            ApplicationEnvironment::initializeLocale($localeName);

            return $localeName;
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer(ContainerInterface $container): LocaleTransfer
    {
        return $this->getFactory()->getLocalePlugin()->getLocaleTransfer($container);
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @param string $localeName
     *
     * @return void
     */
    protected function setStoreCurrentLocale(string $localeName): void
    {
        /* Required by infrastructure, exists only for BC reasons with DMS mode. */
        if ($this->getFactory()->getStoreClient()->isDynamicStoreEnabled()) {
            return;
        }

        $this->getFactory()->getStore()->setCurrentLocale($localeName);
    }
}
