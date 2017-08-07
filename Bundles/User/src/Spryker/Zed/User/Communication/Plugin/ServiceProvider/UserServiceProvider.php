<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\User\Business\UserFacade getFacade()
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 */
class UserServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @var \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     */
    public function __construct(Container $container)
    {
        $this->userFacade = $container->getLocator()->user()->facade();
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig.global.variables'] = $app->share(
            $app->extend('twig.global.variables', function (array $variables) {
                $variables['username'] = $this->getUsername();

                return $variables;
            })
        );
    }

    /**
     * @return string
     */
    protected function getUsername()
    {
        $username = '';

        if ($this->userFacade->hasCurrentUser()) {
            $user = $this->userFacade->getCurrentUser();
            $username = sprintf('%s %s', $user->getFirstName(), $user->getLastName());
        }

        return $username;
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
