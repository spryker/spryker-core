<?php

namespace SprykerFeature\Zed\Distributor\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Distributor\Business\DistributorFacade;
use SprykerFeature\Zed\Installer\Communication\Plugin\InstallerInterface;

/**
 * @method DistributorFacade getFacade()
 */
class ItemTypeInstaller extends AbstractPlugin implements InstallerInterface
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getFacade()->installItemTypes();
    }

}
