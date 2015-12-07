<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Communication\Plugin;

use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use SprykerFeature\Zed\SearchPage\Communication\SearchPageDependencyContainer;

/**
 * @method SearchPageDependencyContainer getDependencyContainer()
 */
class TemplateInstaller extends AbstractInstallerPlugin
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getDependencyContainer()
            ->getSearchPageFacade()
            ->installTemplates($this->messenger);
    }

}
