<?php

namespace SprykerFeature\Zed\ProductSearch\Communication;

use SprykerFeature\Zed\Installer\Business\Model\InstallerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductSearch\Business\ProductSearchFacade;
use SprykerFeature\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class ProductSearchDependencyContainer extends AbstractDependencyContainer
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
     * @return ProductSearchFacade
     */
    public function getInstallerFacade()
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
