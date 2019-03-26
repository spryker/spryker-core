<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ZedNavigation\Communication\Plugin\ZedNavigation;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @deprecated Use `Spryker\Zed\ZedNavigation\Communication\Plugin\Twig\ZedNavigationTwigPlugin` instead.
 *
 * @method \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface getFacade()
 * @method \Spryker\Zed\ZedNavigation\Communication\ZedNavigationCommunicationFactory getFactory()
 * @method \Spryker\Zed\ZedNavigation\ZedNavigationConfig getConfig()
 */
class ZedNavigationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const URI_SUFFIX_INDEX = '\/index$';
    public const URI_SUFFIX_SLASH = '\/$';

    /**
     * @var array|null
     */
    protected $navigation;

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    public function register(Application $application)
    {
        $application['twig'] = $application->share(
            $application->extend('twig', function (Environment $twig) use ($application) {
                $twig->addFunction($this->getNavigationFunction($application));
                $twig->addFunction($this->getBreadcrumbFunction($application));

                return $twig;
            })
        );

        $this->addBackwardCompatibility($application);
    }

    /**
     * @param \Silex\Application $application
     *
     * @return \Twig\TwigFunction
     */
    protected function getNavigationFunction(Application $application)
    {
        $navigation = new TwigFunction('navigation', function () use ($application) {
            $navigation = $this->buildNavigation($application);

            return $navigation;
        });

        return $navigation;
    }

    /**
     * @param \Silex\Application $application
     *
     * @return \Twig\TwigFunction
     */
    protected function getBreadcrumbFunction(Application $application)
    {
        $navigation = new TwigFunction('breadcrumb', function () use ($application) {
            $navigation = $this->buildNavigation($application);

            return $navigation['path'];
        });

        return $navigation;
    }

    /**
     * @param \Silex\Application $application
     *
     * @return array
     */
    protected function buildNavigation(Application $application)
    {
        if (!$this->navigation) {
            $request = $this->getRequest($application);
            $uri = $this->removeUriSuffix($request->getPathInfo());
            $this->navigation = (new ZedNavigation())
                ->buildNavigation($uri);
        }

        return $this->navigation;
    }

    /**
     * @param \Silex\Application $application
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(Application $application)
    {
        return $application['request_stack']->getCurrentRequest();
    }

    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    public function boot(Application $application)
    {
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function removeUriSuffix($path)
    {
        return preg_replace('/' . self::URI_SUFFIX_INDEX . '|' . self::URI_SUFFIX_SLASH . '/m', '', $path);
    }

    /**
     * Method to keep ZedNavigation module BC. This and `getNavigation()` can be removed in next major.
     *
     * @param \Silex\Application $application
     *
     * @return void
     */
    private function addBackwardCompatibility(Application $application)
    {
        $application['twig.global.variables'] = $application->share(
            $application->extend('twig.global.variables', function (array $variables) {
                $navigation = $this->getNavigation();
                $breadcrumbs = $navigation['path'];

                $variables['navigation'] = $navigation;
                $variables['breadcrumbs'] = $breadcrumbs;

                return $variables;
            })
        );
    }

    /**
     * @return array
     */
    protected function getNavigation()
    {
        $request = Request::createFromGlobals();
        $uri = $this->removeUriSuffix($request->getPathInfo());

        return (new ZedNavigation())
            ->buildNavigation($uri);
    }
}
