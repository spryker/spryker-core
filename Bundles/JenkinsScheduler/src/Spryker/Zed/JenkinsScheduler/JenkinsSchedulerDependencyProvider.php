<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler;

use GuzzleHttp\Client;
use Spryker\Zed\JenkinsScheduler\Dependency\Guzzle\JenkinsSchedulerToGuzzleBridge;
use Spryker\Zed\JenkinsScheduler\Dependency\Service\JenkinsSchedulerToUtilEncodingServiceBridge;
use Spryker\Zed\JenkinsScheduler\Dependency\TwigEnvironment\JenkinsSchedulerToTwigEnvironmentBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;
use Twig\Environment;

/**
 * @method \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig getConfig()
 */
class JenkinsSchedulerDependencyProvider extends AbstractBundleDependencyProvider
{
    public const GUZZLE_CLIENT = 'GUZZLE_CLIENT';

    public const TWIG_ENVIRONMENT = 'TWIG_ENVIRONMENT';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addGuzzleClient($container);
        $container = $this->addTwigEnvironment($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGuzzleClient(Container $container): Container
    {
        $container[static::GUZZLE_CLIENT] = function (Container $container) {
            return new JenkinsSchedulerToGuzzleBridge(new Client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigEnvironment(Container $container): Container
    {
        $container[static::TWIG_ENVIRONMENT] = function () {
            $twig = $this->getTwigEnvironment();

            return new JenkinsSchedulerToTwigEnvironmentBridge($twig);
        };

        return $container;
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment(): Environment
    {
        $pimplePlugin = new Pimple();
        $twig = $pimplePlugin->getApplication()['twig'];

        return $twig;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new JenkinsSchedulerToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        };

        return $container;
    }
}
