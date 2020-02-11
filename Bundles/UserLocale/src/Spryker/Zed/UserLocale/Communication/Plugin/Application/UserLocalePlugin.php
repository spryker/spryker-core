<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use `Spryker\Zed\UserLocale\Communication\Plugin\Locale\UserLocaleLocalePlugin` instead.
 *
 * @method \Spryker\Zed\UserLocale\Communication\UserLocaleCommunicationFactory getFactory()
 * @method \Spryker\Zed\UserLocale\UserLocaleConfig getConfig()
 * @method \Spryker\Zed\UserLocale\Business\UserLocaleFacadeInterface getFacade()
 */
class UserLocalePlugin extends AbstractPlugin implements ApplicationPluginInterface, BootableApplicationPluginInterface
{
    /**
     * {@inheritDoc}
     * - Replaces default Application locale with User Locale.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface
    {
        $userLocaleName = $this->getCurrentUserLocaleCode();

        if ($userLocaleName !== null) {
            $this->getFactory()->getStore()->setCurrentLocale($userLocaleName);
        }

        return $container;
    }

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
        return $container;
    }

    /**
     * @return string|null
     */
    protected function getCurrentUserLocaleCode(): ?string
    {
        if (!$this->getFactory()->getUserFacade()->hasCurrentUser()) {
            return null;
        }

        return $this->getFactory()->getUserFacade()->getCurrentUser()->getLocaleName();
    }
}
