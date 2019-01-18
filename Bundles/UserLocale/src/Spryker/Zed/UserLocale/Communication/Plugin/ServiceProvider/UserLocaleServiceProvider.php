<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\UserLocale\Communication\UserLocaleCommunicationFactory getFactory()
 * @method \Spryker\Zed\UserLocale\UserLocaleConfig getConfig()
 * @method \Spryker\Zed\UserLocale\Business\UserLocaleFacadeInterface getFacade()
 */
class UserLocaleServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['locale'] = $this->getCurrentUserLocaleCode() ?: $this->getConfig()->getDefaultLocaleName();
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
