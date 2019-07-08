<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Application\Communication\Plugin\Exception\InvalidUrlConfigurationException;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Will be removed without replacement. We should not throw an exception if the requested domain is not equal to configured one as it is not a security issue neither non-valid configuration.
 *
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class AssertUrlConfigurationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
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
        $app->before(function (Request $request) {
            $this->assertMatchingHostName($request);
        }, Application::EARLY_EVENT);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\Application\Communication\Plugin\Exception\InvalidUrlConfigurationException
     *
     * @return void
     */
    protected function assertMatchingHostName(Request $request)
    {
        $hostName = $request->getHost();
        if (!$hostName) {
            return;
        }

        $configuredHostName = $this->getConfig()->getHostName();
        if ($configuredHostName === $hostName) {
            return;
        }

        throw new InvalidUrlConfigurationException(sprintf(
            'Incorrect HOST_ZED config, expected `%s`, got `%s`. Set the URLs in your Shared/config_default_%s.php or env specific config files.',
            $hostName,
            $configuredHostName,
            Store::getInstance()->getStoreName()
        ));
    }
}
