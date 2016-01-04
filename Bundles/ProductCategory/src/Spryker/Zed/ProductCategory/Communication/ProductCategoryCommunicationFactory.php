<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Communication;

use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormDelete;
use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\ProductCategory\Communication\Table\ProductCategoryTable;
use Spryker\Zed\ProductCategory\Communication\Table\ProductTable;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCmsBridge;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryBridge;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductBridge;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use Spryker\Zed\ProductCategory\ProductCategoryDependencyProvider;
use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormAdd;
use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormEdit;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductCategory\ProductCategoryConfig;

/**
 * @method ProductCategoryQueryContainer getQueryContainer()
 * @method ProductCategoryConfig getConfig()
 */
class ProductCategoryCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return LocaleTransfer
     */
    public function createCurrentLocale()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_LOCALE)
            ->getCurrentLocale();
    }

    /**
     * @throws \ErrorException
     *
     * @return ProductCategoryToProductBridge
     */
    public function createProductFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @throws \ErrorException
     *
     * @return ProductCategoryToCategoryBridge
     */
    public function createCategoryFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * TODO: https://spryker.atlassian.net/browse/CD-540
     *
     * @throws \ErrorException
     *
     * @return ProductCategoryToCmsBridge
     */
    public function createCmsFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CMS);
    }

    /**
     * @return CategoryQueryContainerInterface
     */
    public function createCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::CATEGORY_QUERY_CONTAINER);
    }

    /**
     * @return ProductQueryContainerInterface
     */
    public function createProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::PRODUCT_QUERY_CONTAINER);
    }

    /**
     * @param int $idParentNode
     *
     * @return CategoryFormAdd
     */
    public function createCategoryFormAdd($idParentNode)
    {
        $form = new CategoryFormAdd(
            $this->createCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->createCurrentLocale(),
            null,
            $idParentNode
        );

        return $this->createForm($form);
    }

    /**
     * @param int $idCategory
     *
     * @return CategoryFormEdit
     */
    public function createCategoryFormEdit($idCategory)
    {
        return new CategoryFormEdit(
            $this->createCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->createCurrentLocale(),
            $idCategory,
            null
        );
    }

    /**
     * @param int $idCategory
     *
     * @return CategoryFormEdit
     */
    public function createCategoryFormDelete($idCategory)
    {
        return new CategoryFormDelete(
            $this->createCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->createCurrentLocale(),
            $idCategory,
            null
        );
    }

    /**
     * @param LocaleTransfer $locale
     * @param int $idCategory
     *
     * @return ProductCategoryTable
     */
    public function createProductCategoryTable(LocaleTransfer $locale, $idCategory)
    {
        return new ProductCategoryTable($this->getQueryContainer(), $locale, $idCategory);
    }

    /**
     * @param LocaleTransfer $locale
     * @param int $idCategory
     *
     * @return ProductTable
     */
    public function createProductTable(LocaleTransfer $locale, $idCategory)
    {
        return new ProductTable($this->getQueryContainer(), $locale, $idCategory);
    }

    /**
     * @throws \ErrorException
     *
     * @return ConnectionInterface
     */
    public function createPropelConnection()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::PLUGIN_PROPEL_CONNECTION);
    }

}
