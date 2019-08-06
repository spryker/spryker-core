<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use `\Spryker\Zed\Application\Communication\Plugin\Twig\ApplicationTwigPlugin` instead.
 * The global twig variables `currentController` and `store` are removed in the `ApplicationTwigPlugin`.
 * If you have those in your twig files, please add them on your own.
 *
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class TwigGlobalVariablesServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig.global.variables'] = $app->share(
            $app->extend('twig.global.variables', function (array $variables) {
                $variables += [
                    'environment' => APPLICATION_ENV,
                    'store' => Store::getInstance()->getStoreName(),
                    'title' => Config::get(KernelConstants::PROJECT_NAMESPACE) . ' | Zed | ' . ucfirst(APPLICATION_ENV),
                    'currentController' => static::class,
                ];

                return $variables;
            })
        );
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
