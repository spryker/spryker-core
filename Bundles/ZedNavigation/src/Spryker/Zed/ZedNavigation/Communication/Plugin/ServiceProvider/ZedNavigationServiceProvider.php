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

/**
 * @method \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacade getFacade()
 * @method \Spryker\Zed\ZedNavigation\Communication\ZedNavigationCommunicationFactory getFactory()
 */
class ZedNavigationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    const URI_SUFFIX_INDEX = '\/index$';
    const URI_SUFFIX_SLASH = '\/$';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig.global.variables'] = $app->share(
            $app->extend('twig.global.variables', function (array $variables) {
                $navigation = $this->getNavigation();
                $breadcrumbs = $navigation['path'];

                $variables['navigation'] = $navigation;
                $variables['breadcrumbs'] = $breadcrumbs;

                return $variables;
            })
        );
    }

    /**
     * @return string
     */
    protected function getNavigation()
    {
        $request = Request::createFromGlobals();
        $uri = $this->removeUriSuffix($request->getPathInfo());

        return (new ZedNavigation())
            ->buildNavigation($uri);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
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

}
