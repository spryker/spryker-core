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
use Symfony\Component\Form\FormInterface;

/**
 * @method ProductCategoryQueryContainer getQueryContainer()
 * @method ProductCategoryConfig getConfig()
 */
class ProductCategoryCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @deprecated Use getCurrentLocale() instead.
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createCurrentLocale()
    {
        trigger_error('Deprecated, use getCurrentLocale() instead.', E_USER_DEPRECATED);

        return $this->getCurrentLocale();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_LOCALE)
            ->getCurrentLocale();
    }

    /**
     * @deprecated Use getProductFacade() instead.
     *
     * @throws \ErrorException
     *
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductBridge
     */
    public function createProductFacade()
    {
        trigger_error('Deprecated, use getProductFacade() instead.', E_USER_DEPRECATED);

        return $this->getProductFacade();
    }

    /**
     * @throws \ErrorException
     *
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductBridge
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @deprecated Use getCategoryFacade() instead.
     *
     * @throws \ErrorException
     *
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryBridge
     */
    public function createCategoryFacade()
    {
        trigger_error('Deprecated, use getCategoryFacade() instead.', E_USER_DEPRECATED);

        return $this->getCategoryFacade();
    }

    /**
     * @throws \ErrorException
     *
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryBridge
     */
    public function getCategoryFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @deprecated Use getCmsFacade() instead.
     *
     * @throws \ErrorException
     *
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCmsBridge
     */
    public function createCmsFacade()
    {
        trigger_error('Deprecated, use getCmsFacade() instead.', E_USER_DEPRECATED);

        return $this->getCmsFacade();
    }

    /**
     * TODO: https://spryker.atlassian.net/browse/CD-540
     *
     * @throws \ErrorException
     *
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCmsBridge
     */
    public function getCmsFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CMS);
    }

    /**
     * @deprecated Use getCategoryQueryContainer() instead.
     *
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    public function createCategoryQueryContainer()
    {
        trigger_error('Deprecated, use getCategoryQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getCategoryQueryContainer();
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::CATEGORY_QUERY_CONTAINER);
    }

    /**
     * @deprecated Use getProductQueryContainer() instead.
     *
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function createProductQueryContainer()
    {
        trigger_error('Deprecated, use getProductQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getProductQueryContainer();
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::PRODUCT_QUERY_CONTAINER);
    }

    /**
     * @param int $idParentNode
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryFormAdd($idParentNode)
    {
        $form = new CategoryFormAdd(
            $this->getCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->getCurrentLocale(),
            null,
            $idParentNode
        );

        return $this->createForm($form);
    }

    /**
     * @param int $idCategory
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryFormEdit($idCategory)
    {
        $form = new CategoryFormEdit(
            $this->getCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->getCurrentLocale(),
            $idCategory,
            null
        );

        return $this->createForm($form);
    }

    /**
     * @param int $idCategory
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryFormDelete($idCategory)
    {
        $form = new CategoryFormDelete(
            $this->getCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->getCurrentLocale(),
            $idCategory,
            null
        );

        return $this->createForm($form);
    }

    /**
     * @param LocaleTransfer $locale
     * @param int $idCategory
     *
     * @return \Spryker\Zed\ProductCategory\Communication\Table\ProductCategoryTable
     */
    public function createProductCategoryTable(LocaleTransfer $locale, $idCategory)
    {
        return new ProductCategoryTable($this->getQueryContainer(), $locale, $idCategory);
    }

    /**
     * @param LocaleTransfer $locale
     * @param int $idCategory
     *
     * @return \Spryker\Zed\ProductCategory\Communication\Table\ProductTable
     */
    public function createProductTable(LocaleTransfer $locale, $idCategory)
    {
        return new ProductTable($this->getQueryContainer(), $locale, $idCategory);
    }

    /**
     * @deprecated Use getPropelConnection() instead.
     *
     * @throws \ErrorException
     *
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    public function createPropelConnection()
    {
        trigger_error('Deprecated, use getPropelConnection() instead.', E_USER_DEPRECATED);

        return $this->getPropelConnection();
    }

    /**
     * @throws \ErrorException
     *
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    public function getPropelConnection()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::PLUGIN_PROPEL_CONNECTION);
    }

}
