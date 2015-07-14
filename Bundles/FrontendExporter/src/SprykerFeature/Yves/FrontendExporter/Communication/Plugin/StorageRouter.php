<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\FrontendExporter\Communication\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Yves\FrontendExporter\Communication\FrontendExporterDependencyContainer;
use SprykerFeature\Yves\FrontendExporter\Communication\Router\StorageRouter;

/**
 * Class StorageRouterPlugin
 */
/**
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class StorageRouter extends AbstractPlugin
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
