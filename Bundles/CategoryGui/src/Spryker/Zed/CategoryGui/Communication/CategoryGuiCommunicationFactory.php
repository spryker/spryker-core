<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryGui\CategoryGuiDependencyProvider;
use Spryker\Zed\CategoryGui\Communication\Category\CategoryCreator;
use Spryker\Zed\CategoryGui\Communication\Category\CategoryCreatorInterface;
use Spryker\Zed\CategoryGui\Communication\Category\CategoryDeleter;
use Spryker\Zed\CategoryGui\Communication\Category\CategoryDeleterInterface;
use Spryker\Zed\CategoryGui\Communication\Category\CategoryNodeOrderUpdater;
use Spryker\Zed\CategoryGui\Communication\Category\CategoryNodeOrderUpdaterInterface;
use Spryker\Zed\CategoryGui\Communication\Category\CategoryUpdater;
use Spryker\Zed\CategoryGui\Communication\Category\CategoryUpdaterInterface;
use Spryker\Zed\CategoryGui\Communication\Expander\CategoryExpander;
use Spryker\Zed\CategoryGui\Communication\Expander\CategoryExpanderInterface;
use Spryker\Zed\CategoryGui\Communication\Finder\CategoryFinder;
use Spryker\Zed\CategoryGui\Communication\Finder\CategoryFinderInterface;
use Spryker\Zed\CategoryGui\Communication\Finder\CategoryStoreWithStateFinder;
use Spryker\Zed\CategoryGui\Communication\Finder\CategoryStoreWithStateFinderInterface;
use Spryker\Zed\CategoryGui\Communication\Form\CategoryType;
use Spryker\Zed\CategoryGui\Communication\Form\Constraint\CategoryKeyUniqueConstraint;
use Spryker\Zed\CategoryGui\Communication\Form\Constraint\CategoryLocalizedAttributeNameUniqueConstraint;
use Spryker\Zed\CategoryGui\Communication\Form\DataProvider\CategoryDeleteDataProvider;
use Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Create\CategoryCreateDataProvider;
use Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Create\RootCategoryCreateDataProvider;
use Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Edit\CategoryEditDataProvider;
use Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Edit\RootCategoryEditDataProvider;
use Spryker\Zed\CategoryGui\Communication\Form\DeleteType;
use Spryker\Zed\CategoryGui\Communication\Form\EventListener\CategoryStoreRelationFieldEventSubscriber;
use Spryker\Zed\CategoryGui\Communication\Form\RootCategoryType;
use Spryker\Zed\CategoryGui\Communication\Form\Transformer\CategoryExtraParentsTransformer;
use Spryker\Zed\CategoryGui\Communication\Mapper\CategoryNodeMapper;
use Spryker\Zed\CategoryGui\Communication\Mapper\CategoryNodeMapperInterface;
use Spryker\Zed\CategoryGui\Communication\Mapper\CategoryStoreWithStateMapper;
use Spryker\Zed\CategoryGui\Communication\Mapper\CategoryStoreWithStateMapperInterface;
use Spryker\Zed\CategoryGui\Communication\Table\CategoryTable;
use Spryker\Zed\CategoryGui\Communication\Tabs\CategoryFormTabs;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToStoreFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToTranslatorFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Service\CategoryGuiToUtilEncodingServiceInterface;
use Spryker\Zed\Gui\Communication\Tabs\TabsInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Constraint;

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
        return new CategoryTable(
            $this->getLocaleFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @param int|null $idParentNode
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryCreateForm(?int $idParentNode): FormInterface
    {
        $categoryCreateDataFormProvider = $this->createCategoryCreateDataProvider();

        return $this->getFormFactory()->create(
            CategoryType::class,
            $categoryCreateDataFormProvider->getData($idParentNode),
            $categoryCreateDataFormProvider->getOptions(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Create\CategoryCreateDataProvider
     */
    public function createCategoryCreateDataProvider(): CategoryCreateDataProvider
    {
        return new CategoryCreateDataProvider(
            $this->getRepository(),
            $this->createCategoryFinder(),
            $this->createCategoryExpander(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryEditForm(CategoryTransfer $categoryTransfer): FormInterface
    {
        $categoryCreateDataFormProvider = $this->createCategoryEditDataProvider();

        return $this->getFormFactory()->create(
            CategoryType::class,
            $categoryTransfer,
            $categoryCreateDataFormProvider->getOptions($categoryTransfer->getIdCategoryOrFail()),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Edit\CategoryEditDataProvider
     */
    public function createCategoryEditDataProvider(): CategoryEditDataProvider
    {
        return new CategoryEditDataProvider(
            $this->getRepository(),
            $this->createCategoryFinder(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRootCategoryEditForm(CategoryTransfer $categoryTransfer): FormInterface
    {
        $rootCategoryCreateDataFormProvider = $this->createRootCategoryEditDataProvider();

        return $this->getFormFactory()->create(
            RootCategoryType::class,
            $categoryTransfer,
            $rootCategoryCreateDataFormProvider->getOptions(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Edit\RootCategoryEditDataProvider
     */
    public function createRootCategoryEditDataProvider(): RootCategoryEditDataProvider
    {
        return new RootCategoryEditDataProvider(
            $this->getRepository(),
            $this->createCategoryFinder(),
        );
    }

    /**
     * @param int $idCategory
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCategoryDeleteForm(int $idCategory): FormInterface
    {
        $categoryDeleteFormDataProvider = $this->createCategoryDeleteDataProvider();

        return $this->getFormFactory()->create(
            DeleteType::class,
            $categoryDeleteFormDataProvider->getData($idCategory),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\DataProvider\CategoryDeleteDataProvider
     */
    public function createCategoryDeleteDataProvider(): CategoryDeleteDataProvider
    {
        return new CategoryDeleteDataProvider($this->getCategoryFacade());
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Category\CategoryCreatorInterface
     */
    public function createCategoryCreator(): CategoryCreatorInterface
    {
        return new CategoryCreator(
            $this->getCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Category\CategoryUpdaterInterface
     */
    public function createCategoryUpdater(): CategoryUpdaterInterface
    {
        return new CategoryUpdater(
            $this->getCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Category\CategoryDeleterInterface
     */
    public function createCategoryDeleter(): CategoryDeleterInterface
    {
        return new CategoryDeleter(
            $this->getCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Category\CategoryNodeOrderUpdaterInterface
     */
    public function createCategoryNodeOrderUpdater(): CategoryNodeOrderUpdaterInterface
    {
        return new CategoryNodeOrderUpdater(
            $this->getCategoryFacade(),
            $this->getUtilEncodingService(),
            $this->createCategoryNodeMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createCategoryFormTabs(): TabsInterface
    {
        return new CategoryFormTabs($this->getCategoryFormTabExpanderPlugins());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRootCategoryCreateForm(): FormInterface
    {
        $rootCategoryCreateDataFormProvider = $this->createRootCategoryCreateDataProvider();

        return $this->getFormFactory()->create(
            RootCategoryType::class,
            $rootCategoryCreateDataFormProvider->getData(),
            $rootCategoryCreateDataFormProvider->getOptions(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Create\RootCategoryCreateDataProvider
     */
    public function createRootCategoryCreateDataProvider(): RootCategoryCreateDataProvider
    {
        return new RootCategoryCreateDataProvider(
            $this->getRepository(),
            $this->createCategoryExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Finder\CategoryStoreWithStateFinderInterface
     */
    public function createCategoryStoreWithStateFinder(): CategoryStoreWithStateFinderInterface
    {
        return new CategoryStoreWithStateFinder(
            $this->getCategoryFacade(),
            $this->getStoreFacade(),
            $this->createCategoryStoreWithStateMapper(),
        );
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createCategoryStoreRelationFieldEventSubscriber(): EventSubscriberInterface
    {
        return new CategoryStoreRelationFieldEventSubscriber(
            $this->createCategoryStoreWithStateFinder(),
            $this->getStoreRelationFormTypePlugin(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Mapper\CategoryStoreWithStateMapperInterface
     */
    public function createCategoryStoreWithStateMapper(): CategoryStoreWithStateMapperInterface
    {
        return new CategoryStoreWithStateMapper();
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createCategoryLocalizedAttributeNameUniqueConstraint(): Constraint
    {
        return new CategoryLocalizedAttributeNameUniqueConstraint([
            CategoryLocalizedAttributeNameUniqueConstraint::OPTION_CATEGORY_FACADE => $this->getCategoryFacade(),
            CategoryLocalizedAttributeNameUniqueConstraint::OPTION_TRANSLATOR_FACADE => $this->getTranslatorFacade(),
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createCategoryKeyUniqueConstraint(): Constraint
    {
        return new CategoryKeyUniqueConstraint([
            CategoryKeyUniqueConstraint::OPTION_CATEGORY_GUI_REPOSITORY => $this->getRepository(),
            CategoryKeyUniqueConstraint::OPTION_TRANSLATOR_FACADE => $this->getTranslatorFacade(),
        ]);
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createCategoryExtraParentsTransformer(): DataTransformerInterface
    {
        return new CategoryExtraParentsTransformer();
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Finder\CategoryFinderInterface
     */
    public function createCategoryFinder(): CategoryFinderInterface
    {
        return new CategoryFinder(
            $this->getCategoryFacade(),
            $this->getLocaleFacade(),
            $this->createCategoryExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Expander\CategoryExpanderInterface
     */
    public function createCategoryExpander(): CategoryExpanderInterface
    {
        return new CategoryExpander($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Mapper\CategoryNodeMapperInterface
     */
    public function createCategoryNodeMapper(): CategoryNodeMapperInterface
    {
        return new CategoryNodeMapper();
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): CategoryGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::FACADE_STORE);
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
     * @return \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): CategoryGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return array<\Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormPluginInterface>
     */
    public function getCategoryFormPlugins(): array
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::PLUGINS_CATEGORY_FORM);
    }

    /**
     * @return array<\Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormTabExpanderPluginInterface>
     */
    public function getCategoryFormTabExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::PLUGINS_CATEGORY_FORM_TAB_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryRelationReadPluginInterface>
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

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\Service\CategoryGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CategoryGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }
}
