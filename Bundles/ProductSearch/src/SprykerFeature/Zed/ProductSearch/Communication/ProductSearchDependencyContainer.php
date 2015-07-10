<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\ProductSearch\Business\ProductSearchFacade;
use SprykerFeature\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class ProductSearchDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ProductSearchFacade
     */
    public function getAttributesTransformer()
    {
        return $this->getLocator()->productSearch()->facade();
    }

    /**
     * @return ProductSearchFacade
     */
    public function getProductsTransformer()
    {
        return $this->getLocator()->productSearch()->facade();
    }

    /**
     * @return ProductSearchQueryContainerInterface
     */
    public function getProductSearchQueryContainer()
    {
        return $this->getLocator()->productSearch()->queryContainer();
    }

}
