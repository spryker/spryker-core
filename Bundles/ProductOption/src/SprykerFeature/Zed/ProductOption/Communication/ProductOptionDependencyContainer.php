<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\ProductOption\Business\ProductOptionFacade;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

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
