<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cms\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Yves\Cms\CmsFactory getFactory()
 */
class CmsContentWidgetServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) {
                foreach ($this->getFactory()->getCmsContentWidgetPlugins() as $functionName => $cmsContentWidgetPlugin) {
                    $twig->addFunction(
                        $functionName,
                        new Twig_SimpleFunction(
                            $functionName,
                            $cmsContentWidgetPlugin->getContentWidgetFunction(),
                            $this->getWidgetFunctionOptions()
                        )
                    );
                }
                return $twig;
            })
        );
    }

    /**
     * @return array
     */
    protected function getWidgetFunctionOptions()
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
