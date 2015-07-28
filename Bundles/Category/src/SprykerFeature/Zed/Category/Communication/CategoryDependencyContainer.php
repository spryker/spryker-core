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
use SprykerFeature\Zed\Category\Communication\Table\CategoryAttributeTable;
use SprykerFeature\Zed\Category\Communication\Table\RootNodeTable;
use SprykerFeature\Zed\Category\Communication\Table\UrlTable;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CategoryCommunication getFactory()
 * @method CategoryQueryContainer getQueryContainer()
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
     *
     * @return RootNodeTable
     */
    public function createRootNodeTable()
    {
        $categoryQueryContainer = $this->getQueryContainer();
        $rootNodeQuery = $categoryQueryContainer->queryRootNodes();

        return $this->getFactory()->createTableRootNodeTable($rootNodeQuery);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return CategoryAttributeTable
     */
    public function createCategoryAttributeTable($idCategoryNode)
    {
        if ($idCategoryNode == null) {
            //@TODO: table initialisation with ajax then this part can be deleted
            $idCategoryNode = $this->getQueryContainer()->queryRootNode()->findOne()->getIdCategoryNode();
        }
        $categoryNode = $this->getQueryContainer()->queryCategoryNodeByNodeId($idCategoryNode)->findOne();
        $categoryQueryContainer = $this->getQueryContainer();
        $categoryAttributesQuery = $categoryQueryContainer->queryAttributeByCategoryIdAndLocale(
            $categoryNode->getFkCategory(),
            $this->getCurrentLocale()->getIdLocale()
        );

        return $this->getFactory()->createTableCategoryAttributeTable($categoryAttributesQuery);
    }

    /**
     * @param $idCategoryNode
     *
     * @return UrlTable
     */
    public function createUrlTable($idCategoryNode)
    {
        if ($idCategoryNode == null) {
            //@TODO: table initialisation with ajax then this part can be deleted
            $idCategoryNode = $this->getQueryContainer()->queryRootNode()->findOne()->getIdCategoryNode();
        }
        $urlQuery = $this->getQueryContainer()
            ->queryUrlByIdCategoryNode($idCategoryNode)
        ;
        return $this->getFactory()->createTableUrlTable($urlQuery);
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
