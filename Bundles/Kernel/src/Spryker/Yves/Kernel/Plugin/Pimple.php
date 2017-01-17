<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
