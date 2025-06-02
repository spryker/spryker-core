<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Locale\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Locale\LocaleFactory getFactory()
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 * @method \Spryker\Yves\Locale\LocaleConfig getConfig()
 */
class ConsoleLocaleApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * {@inheritDoc}
     * - Provides locale service for console commands.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_LOCALE, function (ContainerInterface $container): string {
            return $this->getConfig()->getConsoleDefaultLocale();
        });

        return $container;
    }
}
