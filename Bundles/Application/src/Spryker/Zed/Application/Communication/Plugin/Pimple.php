<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin;

use Spryker\Shared\Application\Communication\Application;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 */
class Pimple extends AbstractPlugin
{

    /**
     * @var \Spryker\Shared\Application\Communication\Application
     */
    protected static $application;

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return void
     */
    public static function setApplication(Application $application)
    {
        self::$application = $application;
    }

    /**
     * @return \Spryker\Shared\Application\Communication\Application
     */
    public function getApplication()
    {
        return self::$application;
    }

}
