<?php

namespace SprykerFeature\Zed\ProductOptions\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductOptions\Business\ProductOptionsFacade;

class ProductOptionsDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ProductOptionsFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->productOptions()->facade();
    }
}
