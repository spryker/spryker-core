<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\WebProfiler\Plugin\ServiceProvider;

use Silex\Application;
use Silex\Provider\WebProfilerServiceProvider as SilexWebProfilerServiceProvider;
use Spryker\Shared\Kernel\Store;

class WebProfilerServiceProvider extends SilexWebProfilerServiceProvider
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['profiler.cache_dir'] = function () {
            return APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/cache/profiler';
        };

        parent::register($app);
    }
}
