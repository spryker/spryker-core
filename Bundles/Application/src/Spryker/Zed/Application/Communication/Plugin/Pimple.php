<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin;

use Spryker\Shared\Application\Communication\Application;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Application\Business\ApplicationFacade;
use Spryker\Zed\Application\Communication\ApplicationCommunicationFactory;

/**
 * @method ApplicationFacade getFacade()
 * @method ApplicationCommunicationFactory getFactory()
 */
class Pimple extends AbstractPlugin
{

    /**
     * @var Application
     */
    protected static $application;

    /**
     * @param Application $application
     *
     * @return void
     */
    public static function setApplication(Application $application)
    {
        self::$application = $application;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return self::$application;
    }

}
