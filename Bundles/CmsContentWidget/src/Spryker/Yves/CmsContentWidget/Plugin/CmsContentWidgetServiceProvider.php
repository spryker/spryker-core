<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidget\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @deprecated Use `\Spryker\Yves\CmsContentWidget\Plugin\Twig\CmsContentWidgetTwigPlugin` instead.
 *
 * @method \Spryker\Yves\CmsContentWidget\CmsContentWidgetFactory getFactory()
 */
class CmsContentWidgetServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) {
                return $this->registerCmsContentWidgets($twig);
            })
        );
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function registerCmsContentWidgets(Environment $twig)
    {
        foreach ($this->getFactory()->getCmsContentWidgetPlugins() as $functionName => $cmsContentWidgetPlugin) {
            $twig->addFunction(
                $this->createTwigSimpleFunction($functionName, $cmsContentWidgetPlugin)
            );
        }

        return $twig;
    }

    /**
     * @param string $functionName
     * @param \Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface $cmsContentWidgetPlugin
     *
     * @return \Twig\TwigFunction
     */
    protected function createTwigSimpleFunction($functionName, CmsContentWidgetPluginInterface $cmsContentWidgetPlugin)
    {
        return new TwigFunction(
            $functionName,
            $cmsContentWidgetPlugin->getContentWidgetFunction(),
            $this->getTwigSimpleFunctionOptions()
        );
    }

    /**
     * @return array
     */
    protected function getTwigSimpleFunctionOptions()
    {
        return ['needs_context' => true, 'needs_environment' => true, 'is_safe' => ['html']];
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
