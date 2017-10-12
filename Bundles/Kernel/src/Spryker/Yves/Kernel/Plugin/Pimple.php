<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Plugin;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Yves\Kernel\AbstractPlugin;

class Pimple extends AbstractPlugin
{
    /**
     * @var \Spryker\Shared\Kernel\Communication\Application
     */
    protected static $application;

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     *
     * @return void
     */
    public static function setApplication(Application $application)
    {
        self::$application = $application;
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication()
    {
        return self::$application;
    }
}
