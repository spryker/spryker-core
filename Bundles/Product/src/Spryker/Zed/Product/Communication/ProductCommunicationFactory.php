<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Communication\Table\ProductTable;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Product\ProductConfig;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use Spryker\Zed\ProductOption\Business\ProductOptionFacade;
use Spryker\Zed\Url\Business\UrlFacade;

/**
 * @method ProductQueryContainer getQueryContainer()
 * @method ProductConfig getConfig()
 */
class ProductCommunicationFactory extends AbstractCommunicationFactory
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
        $localeTransfer = $this->createLocaleTransfer();

        return new ProductTable(
            $productQuery,
            $this->createUrlFacade(),
            $localeTransfer,
            $this->getConfig()->getHostYves()
        );
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
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_PRODUCT_OPTION);
    }

    /**
     * @return UrlFacade
     */
    public function createUrlFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_URL);
    }

    /**
     * @return ProductCategoryQueryContainerInterface
     */
    public function createProductCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY);
    }

    /**
     * @return LocaleTransfer
     */
    protected function createLocaleTransfer()
    {
        $locale = $this->createLocaleFacade()->getCurrentLocale();

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($locale->toArray());

        return $localeTransfer;
    }

}
