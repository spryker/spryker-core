<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryGui\CategoryGuiDependencyProvider;
use Spryker\Zed\CategoryGui\Communication\Form\CategoryType;
use Spryker\Zed\CategoryGui\Communication\Form\DataProvider\CategoryCreateDataProvider;
use Spryker\Zed\CategoryGui\Communication\Form\DataProvider\CategoryDeleteDataProvider;
use Spryker\Zed\CategoryGui\Communication\Form\DataProvider\CategoryEditDataProvider;
use Spryker\Zed\CategoryGui\Communication\Form\DeleteType;
use Spryker\Zed\CategoryGui\Communication\Table\CategoryTable;
use Spryker\Zed\CategoryGui\Communication\Table\RootNodeTable;
use Spryker\Zed\CategoryGui\Communication\Table\UrlTable;
use Spryker\Zed\CategoryGui\Communication\Tabs\CategoryFormTabs;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Tabs\TabsInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class CategoryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Table\CategoryTable
     */
    public function createCategoryTable(): CategoryTable
    {
        return new CategoryTable($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Table\RootNodeTable
     */
    public function createRootNodeTable(): RootNodeTable
    {
        return new RootNodeTable(
            $this->getCategoryQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param int|null $idParentNode
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryCreateForm(?int $idParentNode): FormInterface
    {
        $categoryCreateDataFormProvider = $this->createCategoryCreateFormDataProvider();

        return $this->getFormFactory()->create(
            CategoryType::class,
            $categoryCreateDataFormProvider->getData($idParentNode),
            $categoryCreateDataFormProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\DataProvider\CategoryCreateDataProvider
     */
    public function createCategoryCreateFormDataProvider(): CategoryCreateDataProvider
    {
        return new CategoryCreateDataProvider(
            $this->getLocaleFacade(),
            $this->getCategoryFacade(),
            $this->getRepository()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryEditForm(CategoryTransfer $categoryTransfer): FormInterface
    {
        $categoryCreateDataFormProvider = $this->createCategoryEditFormDataProvider();

        return $this->getFormFactory()->create(
            CategoryType::class,
            $categoryTransfer,
            $categoryCreateDataFormProvider->getOptions($categoryTransfer->getIdCategory())
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\DataProvider\CategoryEditDataProvider
     */
    public function createCategoryEditFormDataProvider(): CategoryEditDataProvider
    {
        return new CategoryEditDataProvider(
            $this->getCategoryFacade(),
            $this->getLocaleFacade(),
            $this->getRepository()
        );
    }

    /**
     * @param int $idCategory
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryDeleteForm(int $idCategory): FormInterface
    {
        $categoryDeleteFormDataProvider = $this->createCategoryDeleteFormDataProvider();

        return $this->getFormFactory()->create(
            DeleteType::class,
            $categoryDeleteFormDataProvider->getData($idCategory)
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\DataProvider\CategoryDeleteDataProvider
     */
    public function createCategoryDeleteFormDataProvider(): CategoryDeleteDataProvider
    {
        return new CategoryDeleteDataProvider($this->getCategoryFacade());
    }

    /**
     * @param int|null $idCategoryNode
     *
     * @return \Spryker\Zed\CategoryGui\Communication\Table\UrlTable
     */
    public function createUrlTable(?int $idCategoryNode): UrlTable
    {
        return new UrlTable(
            $this->getCategoryQueryContainer(),
            $idCategoryNode
        );
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createCategoryFormTabs(): TabsInterface
    {
        return new CategoryFormTabs(
            $this->getCategoryFormTabExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): CategoryGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CategoryGuiToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer(): CategoryGuiToCategoryQueryContainerInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormPluginInterface[]
     */
    public function getCategoryFormPlugins(): array
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::PLUGINS_CATEGORY_FORM);
    }

    /**
     * @return \Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormTabExpanderPluginInterface[]
     */
    public function getCategoryFormTabExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::PLUGINS_CATEGORY_FORM_TAB_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryRelationReadPluginInterface[]
     */
    public function getCategoryRelationReadPlugins(): array
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::PLUGINS_CATEGORY_RELATION_READ);
    }

    /**
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    public function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::SERVICE_FORM_CSRF_PROVIDER);
    }
}
