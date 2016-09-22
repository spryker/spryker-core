<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication;

use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Communication\Form\CategoryCreateType;
use Spryker\Zed\Category\Communication\Form\DataProvider\CategoryCreateDataProvider;
use Spryker\Zed\Category\Communication\Table\CategoryAttributeTable;
use Spryker\Zed\Category\Communication\Table\RootNodeTable;
use Spryker\Zed\Category\Communication\Table\UrlTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 */
class CategoryCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @var string
     */
    protected $currentLocale;

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale()
    {
        if ($this->currentLocale === null) {
            $this->currentLocale = $this->getLocaleFacade()
                ->getCurrentLocale();
        }

        return $this->currentLocale;
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Category\Communication\Table\RootNodeTable
     */
    public function createRootNodeTable()
    {
        $categoryQueryContainer = $this->getQueryContainer();
        $locale = $this->getCurrentLocale();

        return new RootNodeTable($categoryQueryContainer, $locale->getIdLocale());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryCreateForm()
    {
        $categoryCreateForm = new CategoryCreateType();
        $categoryCreateDataFormProvider = $this->createCategoryCreateFormDataProvider();
        $formFactory = $this->getFormFactory();

        return $formFactory->create(
            $categoryCreateForm,
            $categoryCreateDataFormProvider->getData(),
            $categoryCreateDataFormProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Communication\Form\DataProvider\CategoryCreateDataProvider
     */
    protected function createCategoryCreateFormDataProvider()
    {
        return new CategoryCreateDataProvider(
            $this->getQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param int $idCategoryNode
     *
     * @return \Spryker\Zed\Category\Communication\Table\CategoryAttributeTable
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
            $this->getCurrentLocale()->getIdLocale()
        );

        return new CategoryAttributeTable($categoryAttributesQuery);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return \Spryker\Zed\Category\Communication\Table\UrlTable
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

}
