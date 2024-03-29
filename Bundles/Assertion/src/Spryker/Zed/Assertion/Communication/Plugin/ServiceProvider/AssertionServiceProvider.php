<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Assertion\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Assertion\Business\AssertionFacade;

/**
 * @deprecated Will be removed without replacement.
 */
class AssertionServiceProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    public const ASSERTION = 'assertion';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app[static::ASSERTION] = function () {
            return new AssertionFacade();
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
