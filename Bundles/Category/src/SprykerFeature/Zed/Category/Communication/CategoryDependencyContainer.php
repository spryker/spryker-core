<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\CategoryCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\CategoryDependencyProvider;
use SprykerFeature\Zed\Category\Communication\Form\CategoryNodeForm;
use SprykerFeature\Zed\Category\Communication\Table\CategoryAttributeTable;
use SprykerFeature\Zed\Category\Communication\Table\RootNodeTable;
use SprykerFeature\Zed\Category\Communication\Table\UrlTable;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CategoryCommunication getFactory()
 * @method CategoryQueryContainer getQueryContainer()
 */
class CategoryDependencyContainer extends AbstractCommunicationDependencyContainer
{

    protected $currentLocale;

    /**
     * @return LocaleTransfer
     */
    public function createCurrentLocale()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_LOCALE)
            ->getCurrentLocale();
    }

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale()
    {
        if ($this->currentLocale === null) {
            $this->currentLocale = $this->createCurrentLocale();
        }

        return $this->currentLocale;
    }

    /**
     * @return RootNodeTable
     */
    public function createRootNodeTable()
    {
        $categoryQueryContainer = $this->getQueryContainer();
        $locale = $this->getCurrentLocale();

        return new RootNodeTable($categoryQueryContainer, $locale->getIdLocale());
    }

    /**
     * @param int $idCategoryNode
     *
     * @return CategoryAttributeTable
     */
    public function createCategoryAttributeTable($idCategoryNode)
    {
        if ($idCategoryNode === null) {
            //@TODO: table initialisation with ajax then this part can be deleted
            $idCategoryNode = $this->getQueryContainer()->queryRootNode()->findOne()->getIdCategoryNode();
        }
        $categoryNode = $this->getQueryContainer()->queryCategoryNodeByNodeId($idCategoryNode)->findOne();
        $categoryQueryContainer = $this->getQueryContainer();
        $categoryAttributesQuery = $categoryQueryContainer->queryAttributeByCategoryIdAndLocale(
            $categoryNode->getFkCategory(),
            $this->createCurrentLocale()->getIdLocale()
        );

        return new CategoryAttributeTable($categoryAttributesQuery);
    }

    /**
     * @param $idCategoryNode
     *
     * @return UrlTable
     */
    public function createUrlTable($idCategoryNode)
    {
        if ($idCategoryNode === null) {
            //@TODO: table initialisation with ajax then this part can be deleted
            $idCategoryNode = $this->getQueryContainer()->queryRootNode()->findOne()->getIdCategoryNode();
        }
        $urlQuery = $this->getQueryContainer()
            ->queryUrlByIdCategoryNode($idCategoryNode);

        return new UrlTable($urlQuery);
    }

    /**
     * @param Request $request
     */
    public function createCategoryNodeGrid(Request $request)
    {
    }

    /**
     * @param Request $request
     *
     * @return CategoryNodeForm
     */
    public function createCategoryNodeForm(Request $request)
    {
        $locale = $this->getCurrentLocale();

        return new CategoryNodeForm(
            $request,
            $this->getFactory(),
            $locale,
            $this->getQueryContainer()
        );
    }

    /**
     * @throws \ErrorException
     *
     * @return CategoryFacade
     */
    public function createCategoryFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @throws \ErrorException
     *
     * @return CategoryQueryContainer
     */
    public function createCategoryQueryContainer()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

}
