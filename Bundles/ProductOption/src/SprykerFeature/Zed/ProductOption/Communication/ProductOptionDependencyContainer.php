<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductOption\Business\ProductOptionFacade;

class ProductOptionDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ProductOptionFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->productOption()->facade();
    }
}
