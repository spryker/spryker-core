<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\FrontendExporter\Communication\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Yves\FrontendExporter\Communication\FrontendExporterDependencyContainer;

/**
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class StorageRouter extends AbstractPlugin
{

    /**
     * @param Application $application
     * @param bool $sslEnabled
     *
     * @return StorageRouter
     */
    public function createStorageRouter(Application $application, $sslEnabled = null)
    {
        return $this->getDependencyContainer()->createStorageRouter($application, $sslEnabled);
    }

}
