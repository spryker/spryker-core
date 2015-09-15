<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductCategoryCommunication;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainerInterface;
use SprykerFeature\Zed\ProductCategory\Communication\Table\ProductCategoryTable;
use SprykerFeature\Zed\ProductCategory\Communication\Table\ProductTable;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use SprykerFeature\Zed\ProductCategory\ProductCategoryDependencyProvider;
use SprykerFeature\Zed\ProductCategory\Communication\Form\CategoryFormAdd;
use SprykerFeature\Zed\ProductCategory\Communication\Form\CategoryFormEdit;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

/**
 * @method ProductCategoryCommunication getFactory()
 * @method ProductCategoryQueryContainer getQueryContainer()
 */
class ProductCategoryDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @var LocaleTransfer
     */
    protected $currentLocale;

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
     * @return ProductFacade
     */
    public function createProductFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @throws \ErrorException
     *
     * @return CategoryFacade
     */
    public function createCategoryFacade()
    {
        return $this->getLocator()->category()->facade();
    }

    /**
     * @throws \ErrorException
     *
     * @return ProductCategoryFacade
     */
    public function createProductCategoryFacade()
    {
        return $this->getLocator()->productCategory()->facade();
    }

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale()
    {
        if (null === $this->currentLocale) {
            $this->currentLocale = $this->createCurrentLocale();
        }

        return $this->currentLocale;
    }

    /**
     * @return ProductCategoryQueryContainerInterface
     */
    public function createProductCategoryQueryContainer()
    {
        return $this->getLocator()->productCategory()->queryContainer();
    }

    /**
     * @return CategoryQueryContainerInterface
     */
    public function createCategoryQueryContainer()
    {
        return $this->getLocator()->category()->queryContainer();
    }

    /**
     * @return ProductQueryContainerInterface
     */
    public function createProductQueryContainer()
    {
        return $this->getLocator()->product()->queryContainer();
    }

    /**
     * @return CategoryFormAdd
     */
    public function createCategoryFormAdd()
    {
        return $this->getFactory()->createFormCategoryFormAdd(
            $this->createCategoryQueryContainer(),
            $this->createProductCategoryFacade(),
            $this->getCurrentLocale(),
            null
        );
    }

    /**
     * @param int $idCategory
     *
     * @return CategoryFormEdit
     */
    public function createCategoryFormEdit($idCategory)
    {
        return $this->getFactory()->createFormCategoryFormEdit(
            $this->createCategoryQueryContainer(),
            $this->createProductCategoryFacade(),
            $this->getCurrentLocale(),
            $idCategory
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
        $productCategoryQueryContainer = $this->createProductCategoryQueryContainer();

        return $this->getFactory()->createTableProductCategoryTable($productCategoryQueryContainer, $locale, $idCategory);
    }

    /**
     * @param LocaleTransfer $locale
     * @param int $idCategory
     *
     * @return ProductTable
     */
    public function createProductTable(LocaleTransfer $locale, $idCategory)
    {
        $productCategoryQueryContainer = $this->createProductCategoryQueryContainer();

        return $this->getFactory()->createTableProductTable($productCategoryQueryContainer, $locale, $idCategory);
    }

}
