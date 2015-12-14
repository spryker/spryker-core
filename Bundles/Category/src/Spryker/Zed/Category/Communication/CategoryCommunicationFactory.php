<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Category\Business\CategoryFacade;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Communication\Form\CategoryNodeForm;
use Spryker\Zed\Category\Communication\Table\CategoryAttributeTable;
use Spryker\Zed\Category\Communication\Table\RootNodeTable;
use Spryker\Zed\Category\Communication\Table\UrlTable;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CategoryQueryContainer getQueryContainer()
 */
class CategoryCommunicationFactory extends AbstractCommunicationFactory
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
     *
     * @return CategoryNodeForm
     */
    public function createCategoryNodeForm(Request $request)
    {
        $locale = $this->getCurrentLocale();

        return new CategoryNodeForm($locale);
    }

}
