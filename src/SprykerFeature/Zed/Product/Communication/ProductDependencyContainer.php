<?php

namespace SprykerFeature\Zed\Product\Communication;

use SprykerFeature\Zed\Installer\Business\Model\InstallerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Product\Business\ProductFacade;

class ProductDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ProductFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->product()->facade();
    }
}
