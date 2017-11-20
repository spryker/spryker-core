<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\ContainerGlobals;

class FormFactoryServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $containerGlobals = new ContainerGlobals();
        $containerGlobals[ApplicationConstants::FORM_FACTORY] = $containerGlobals->share(function () use ($app) {
            return $app['form.factory'];
        });
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
