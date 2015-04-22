<?php

namespace SprykerFeature\Zed\FrontendExporter\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Communication\FrontendExporterDependencyContainer;
use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

/**
 * @method FrontendExporterDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractInstallerPlugin
{
    /**
     * @return void
     */
    public function install()
    {
        $this->getDependencyContainer()->getInstallerFacade()->install($this->messenger);
    }
}
