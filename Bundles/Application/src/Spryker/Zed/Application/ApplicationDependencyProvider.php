<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application;

use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Spryker\Shared\Url\UrlBuilder;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\DateFormatterServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\HeaderServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\MvcRoutingServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\NavigationServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\NewRelicServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\RoutingServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\SilexRoutingServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\SslServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\SubRequestServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\TranslationServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\UrlGeneratorServiceProvider;
use Spryker\Zed\Auth\Communication\Plugin\Bootstrap\AuthBootstrapProvider;
use Spryker\Zed\Auth\Communication\Plugin\ServiceProvider\RedirectAfterLoginProvider;
use Spryker\Zed\Gui\Communication\Plugin\ServiceProvider\GuiTwigExtensionServiceProvider;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\BackActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\CreateActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\EditActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\ViewActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\AssetsPathFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\FormatPriceFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\GatewayControllerListenerPlugin;
use Spryker\Zed\Kernel\Communication\Plugin\GatewayServiceProviderPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;

class ApplicationDependencyProvider extends AbstractBundleDependencyProvider
{

    const URL_BUILDER = 'URL_BUILDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::URL_BUILDER] = function () {
            return new UrlBuilder();
        };

        return $container;
    }

    /**
     * @throws \Exception
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getServiceProvider()
    {
        $providers = [
            new RedirectAfterLoginProvider(),
            new RequestServiceProvider(),
            new SslServiceProvider(),
            new ServiceControllerServiceProvider(),
            new AuthBootstrapProvider(),
            new PropelServiceProvider(),
            new RoutingServiceProvider(),
            new MvcRoutingServiceProvider(),
            new SilexRoutingServiceProvider(),
            new ValidatorServiceProvider(),
            new FormServiceProvider(),
            new UrlGeneratorServiceProvider(),
            new NewRelicServiceProvider(),
            new HttpFragmentServiceProvider(),
            new HeaderServiceProvider(),
            new NavigationServiceProvider(),
            new GuiTwigExtensionServiceProvider(
                $this->getTwigFunctions(),
                $this->getTwigFilters()
            ),
            new DateFormatterServiceProvider(),
            new TranslationServiceProvider(),
            new SubRequestServiceProvider(),
        ];

        return $providers;
    }

    /**
     * @return \Spryker\Zed\Library\Twig\TwigFunctionInterface[]
     */
    protected function getTwigFunctions()
    {
        return [
            new FormatPriceFunction(),
            new AssetsPathFunction(),
            new BackActionButtonFunction(),
            new CreateActionButtonFunction(),
            new ViewActionButtonFunction(),
            new EditActionButtonFunction(),
            new UrlFunction(),
        ];
    }

    /**
     * @return \Spryker\Zed\Library\Twig\TwigFilterInterface[]
     */
    protected function getTwigFilters()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Plugin\GatewayServiceProviderPlugin
     */
    protected function getGatewayServiceProvider()
    {
        $controllerListener = new GatewayControllerListenerPlugin();
        $serviceProvider = new GatewayServiceProviderPlugin();
        $serviceProvider->setControllerListener($controllerListener);

        return $serviceProvider;
    }

}
