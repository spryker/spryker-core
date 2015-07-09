<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\FrontendExporter\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\FrontendExporter\FrontendExporterDependencyContainer;
use SprykerFeature\Yves\FrontendExporter\Router\StorageRouter;

/**
 * Class StorageRouterPlugin
 */
/**
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class StorageRouterPlugin extends AbstractPlugin
{

    /**
     * @param Application $application
     * @param null        $sslEnabled
     *
     * @return StorageRouter
     */
    public function createStorageRouter(Application $application, $sslEnabled = null)
    {
        return $this->getDependencyContainer()->createStorageRouter($application, $sslEnabled);
    }

}
