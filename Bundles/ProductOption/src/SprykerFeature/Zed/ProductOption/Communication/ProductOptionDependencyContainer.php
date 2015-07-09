<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\ProductOption\Business\ProductOptionFacade;
use SprykerFeature\Zed\ProductOption\ProductOptionDependencyProvider;

class ProductOptionDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ProductOptionFacade
     */
    public function getInstallerFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_PRODUCT);
    }

}
