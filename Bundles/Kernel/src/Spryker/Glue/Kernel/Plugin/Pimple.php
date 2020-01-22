<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Plugin;

use Spryker\Glue\Kernel\AbstractPlugin;

class Pimple extends AbstractPlugin
{
    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected static $application;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $application
     *
     * @return void
     */
    public static function setApplication($application)
    {
        self::$application = $application;
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function getApplication()
    {
        return self::$application;
    }
}
