<?php

namespace SprykerFeature\Zed\Category\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CategoryCommunication;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
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
     * @return int
     */
    public function createLocaleIdentifier()
    {
        return $this->getLocator()
            ->locale()
            ->facade()
            ->getCurrentIdLocale()
            ;
    }

    /**
     * @param Request $request
     * @return Grid\CategoryGrid
     */
    public function createCategoryGrid(Request $request)
    {
        $idLocale = $this->createLocaleIdentifier();

        return $this->getFactory()->createGridCategoryGrid(
            $this->createQueryContainer()->queryCategory($idLocale),
            $request,
            $this->getLocator()
        );
    }

    /**
     * @param Request $request
     * @return Form\CategoryForm
     */
    public function createCategoryForm(Request $request)
    {
        $idLocale = $this->createLocaleIdentifier();

        return $this->getFactory()->createFormCategoryForm(
            $request,
            $this->getLocator(),
            $this->getFactory(),
            $idLocale,
            $this->createQueryContainer()
        );
    }

    /**
     * @param Request $request
     * @return Grid\CategoryGrid
     */
    public function createCategoryNodeGrid(Request $request)
    {
        $idLocale = $this->createLocaleIdentifier();

        return $this->getFactory()->createGridCategoryNodeGrid(
            $this->createQueryContainer()->queryNodeWithDirectParent($idLocale),
            $request,
            $this->getLocator()
        );
    }

    /**
     * @param Request $request
     * @return Form\CategoryNodeForm
     */
    public function createCategoryNodeForm(Request $request)
    {
        $idLocale = $this->createLocaleIdentifier();

        return $this->getFactory()->createFormCategoryNodeForm(
            $request,
            $this->getLocator(),
            $this->getFactory(),
            $idLocale,
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
