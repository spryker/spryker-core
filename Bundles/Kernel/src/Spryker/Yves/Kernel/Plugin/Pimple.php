<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\Container\GlobalContainer;

/**
 * @deprecated Use {@link \Spryker\Shared\Kernel\ContainerInterface} instead.
 */
class Pimple extends GlobalContainer
{
    /**
     * @var \Silex\Application
     */
    protected static $application;

    /**
     * @param \Spryker\Service\Container\ContainerInterface|\Silex\Application $application
     *
     * @return void
     */
    public static function setApplication($application): void
    {
        if ($application instanceof ContainerInterface) {
            parent::setContainer($application);

            return;
        }

        static::$application = $application;
    }

    /**
     * @return \Spryker\Service\Container\Container|\Silex\Application
     */
    public function getApplication()
    {
        if (static::$application === null) {
            /** @var \Spryker\Service\Container\Container $container */
            $container = parent::getContainer();

            return $container;
        }

        return static::$application;
    }
}
