<?php

namespace SprykerFeature\Zed\Category\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CategoryCommunication;
use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Communication\Form\CategoryForm;
use SprykerFeature\Zed\Category\Communication\Form\CategoryNodeForm;
use SprykerFeature\Zed\Category\Communication\Grid\CategoryGrid;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

class CategoryDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var CategoryCommunication|FactoryInterface
     */
    protected $factory;

    /**
     * @return CategoryFacade
     */
    public function createCategoryFacade()
    {
        return $this->getLocator()->category()->facade();
    }

    /**
     * @return LocaleDto
     */
    public function getCurrentLocale()
    {
        return $this->getLocator()
            ->locale()
            ->facade()
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
            $this->createQueryContainer()->queryCategory($locale->getIdLocale()),
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
            $locale->getIdLocale(),
            $this->createQueryContainer()
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
            $this->createQueryContainer()->queryNodeWithDirectParent($locale->getIdLocale()),
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
            $this->createQueryContainer()
        );
    }

    /**
     * @return CategoryQueryContainer
     */
    protected function createQueryContainer()
    {
        return $this->getLocator()->category()->queryContainer();
    }
}
