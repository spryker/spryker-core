<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Product\Communication\Table\ProductTable;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainer;
use SprykerFeature\Zed\Product\ProductDependencyProvider;
use SprykerFeature\Zed\ProductOption\Business\ProductOptionFacade;

/**
 * @method ProductQueryContainer getQueryContainer()
 */
class ProductDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ProductFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return ProductTable
     */
    public function createProductTable()
    {
        $productQuery = $this->getQueryContainer()->queryAbstractProducts();

        return $this->getFactory()->createTableProductTable($productQuery);
    }

    /**
     * @return LocaleFacade
     */
    public function createLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return ProductOptionFacade
     */
    public function createProductOptionsFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_PRODUCT_OPTIONS);
    }
}
