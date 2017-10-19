<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Twig\TwigFactory getFactory()
 */
class TwigServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    const TWIG_LOADER_YVES = 'twig.loader.yves';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app[static::TWIG_LOADER_YVES] = function () {
            return $this->getFactory()->createFilesystemLoader();
        };
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
