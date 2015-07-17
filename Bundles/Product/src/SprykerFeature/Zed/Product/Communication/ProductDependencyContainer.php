<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Product\Business\ProductFacade;

class ProductDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ProductFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    public function createProductForm(){


        return $this->getFactory()->createFormProductForm($blankConstraint);

    }
}
