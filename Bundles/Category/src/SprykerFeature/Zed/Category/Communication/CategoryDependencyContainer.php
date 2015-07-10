<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CategoryCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Category\CategoryDependencyProvider;
use SprykerFeature\Zed\Category\Communication\Form\CategoryForm;
use SprykerFeature\Zed\Category\Communication\Form\CategoryNodeForm;
use SprykerFeature\Zed\Category\Communication\Grid\CategoryGrid;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CategoryCommunication getFactory()
 */
class CategoryDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_LOCALE)
            ->getCurrentLocale()
        ;
    }

    /**
     * @param Request $request
     *
     * @return CategoryGrid
     */
    public function createCategoryGrid(Request $request)
    {
        $locale = $this->getCurrentLocale();

        return $this->getFactory()->createGridCategoryGrid(
            $this->getQueryContainer()->queryCategory($locale->getIdLocale()),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return CategoryForm
     */
    public function createCategoryForm(Request $request)
    {
        $locale = $this->getCurrentLocale();

        return $this->getFactory()->createFormCategoryForm(
            $request,
            $this->getFactory(),
            $locale,
            $this->getQueryContainer()
        );
    }

    /**
     * @param Request $request
     *
     * @return CategoryGrid
     */
    public function createCategoryNodeGrid(Request $request)
    {
        $locale = $this->getCurrentLocale();

        return $this->getFactory()->createGridCategoryNodeGrid(
            $this->getQueryContainer()->queryNodeWithDirectParent($locale->getIdLocale()),
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return CategoryNodeForm
     */
    public function createCategoryNodeForm(Request $request)
    {
        $locale = $this->getCurrentLocale();

        return $this->getFactory()->createFormCategoryNodeForm(
            $request,
            $this->getFactory(),
            $locale,
            $this->getQueryContainer()
        );
    }

}
