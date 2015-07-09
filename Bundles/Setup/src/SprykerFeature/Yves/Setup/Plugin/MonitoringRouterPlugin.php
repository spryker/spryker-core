<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Setup\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;

/**
 * Class MonitoringRouterPlugin
 */
class MonitoringRouterPlugin extends AbstractPlugin
{

    /**
     * @param Application $app
     * @param bool $sslEnabled
     *
     * @return mixed
     */
    public function createMonitoringRouter(Application $app, $sslEnabled = false)
    {
        return $this->getDependencyContainer()->createMonitoringRouter($app, $sslEnabled);
    }

}
