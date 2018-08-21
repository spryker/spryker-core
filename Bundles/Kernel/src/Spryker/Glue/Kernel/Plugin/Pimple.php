<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Plugin;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\Kernel\Application;

class Pimple extends AbstractPlugin
{
    /**
     * @var \Spryker\Glue\Kernel\Application
     */
    protected static $application;

    /**
     * @param \Spryker\Glue\Kernel\Application $application
     *
     * @return void
     */
    public static function setApplication(Application $application)
    {
        self::$application = $application;
    }

    /**
     * @return \Spryker\Glue\Kernel\Application
     */
    public function getApplication()
    {
        return self::$application;
    }
}
