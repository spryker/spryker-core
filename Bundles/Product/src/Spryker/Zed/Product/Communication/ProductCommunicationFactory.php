<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Product\Communication\Table\ProductTable;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToProductOptionInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Product\ProductConfig;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

/**
 * @method ProductQueryContainer getQueryContainer()
 * @method ProductConfig getConfig()
 */
class ProductCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductTable
     */
    public function createProductTable()
    {
        $productQuery = $this->getQueryContainer()->queryProductAbstract();
        $localeTransfer = $this->createLocaleTransfer();

        return new ProductTable(
            $productQuery,
            $this->getUrlFacade(),
            $localeTransfer,
            $this->getConfig()->getHostYves()
        );
    }

    /**
     * @deprecated, Use getLocaleFacade() instead.
     *
     * @return ProductToLocaleInterface
     */
    public function createLocaleFacade()
    {
        trigger_error('Deprecated, use getLocaleFacade() instead.', E_USER_DEPRECATED);

        return $this->getLocaleFacade();
    }

    /**
     * @return ProductToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @deprecated, Use getProductOptionsFacade() instead.
     *
     * @return ProductToProductOptionInterface
     */
    public function createProductOptionsFacade()
    {
        trigger_error('Deprecated, use getProductOptionsFacade() instead.', E_USER_DEPRECATED);

        return $this->getProductOptionsFacade();
    }

    /**
     * @return ProductToProductOptionInterface
     */
    public function getProductOptionsFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_PRODUCT_OPTION);
    }

    /**
     * @deprecated, Use getUrlFacade() instead.
     *
     * @return ProductToUrlInterface
     */
    public function createUrlFacade()
    {
        trigger_error('Deprecated, use getUrlFacade() instead.', E_USER_DEPRECATED);

        return $this->getUrlFacade();
    }

    /**
     * @return ProductToUrlInterface
     */
    public function getUrlFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_URL);
    }

    /**
     * @deprecated, Use getProductCategoryQueryContainer() instead.
     *
     * @return ProductCategoryQueryContainerInterface
     */
    public function createProductCategoryQueryContainer()
    {
        trigger_error('Deprecated, use getProductCategoryQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getProductCategoryQueryContainer();
    }

    /**
     * @return ProductCategoryQueryContainerInterface
     */
    public function getProductCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY);
    }

    /**
     * @return LocaleTransfer
     */
    protected function createLocaleTransfer()
    {
        $locale = $this->getLocaleFacade()->getCurrentLocale();

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($locale->toArray());

        return $localeTransfer;
    }

}
