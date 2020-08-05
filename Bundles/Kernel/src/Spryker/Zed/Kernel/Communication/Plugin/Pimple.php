<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\Container\GlobalContainer;

/**
 * @method \Spryker\Zed\Kernel\Communication\KernelCommunicationFactory getFactory()
 * @method \Spryker\Zed\Kernel\KernelConfig getConfig()
 * @method \Spryker\Zed\Kernel\Business\KernelFacadeInterface getFacade()
 *
 * @deprecated Use {@link \Spryker\Shared\Kernel\Container\GlobalContainerInterface} instead.
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
     * @return \Silex\Application|\Spryker\Service\Container\Container
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
