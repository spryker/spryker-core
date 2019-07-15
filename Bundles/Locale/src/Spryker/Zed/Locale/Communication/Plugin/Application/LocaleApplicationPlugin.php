<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Locale\Communication\LocaleCommunicationFactory getFactory()
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Business\LocaleFacadeInterface getFacade()
 * @method \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface getQueryContainer()
 */
class LocaleApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_LOCALE = 'locale';

    /**
     * Added for BC reason only.
     */
    protected const BC_FEATURE_FLAG_LOCALE_LISTENER = 'BC_FEATURE_FLAG_LOCALE_LISTENER';

    /**
     * {@inheritdoc}
     * - Provides locale service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container[static::BC_FEATURE_FLAG_LOCALE_LISTENER] = false;
        $container[static::SERVICE_LOCALE] = function (ContainerInterface $container): string {
            $localeTransfer = $this->getFactory()->getLocalePlugin()->getLocaleTransfer($container);

            $this->getFacade()->setCurrentLocale($localeTransfer);

            return $localeTransfer->getLocaleName();
        };

        return $container;
    }
}
