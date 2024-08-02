<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication;

use Generated\Shared\Transfer\ProductLabelAggregateFormTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\AddIdsProductToAssignConstraint;
use Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\AddIdsProductToDeAssignConstraint;
use Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\UniqueProductLabelNameConstraint;
use Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelAggregateFormDataProvider;
use Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelFormDataProvider;
use Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\RelatedProductFormDataProvider;
use Spryker\Zed\ProductLabelGui\Communication\Form\ProductLabelAggregateFormType;
use Spryker\Zed\ProductLabelGui\Communication\Form\ProductLabelDeleteForm;
use Spryker\Zed\ProductLabelGui\Communication\Table\AssignedProductTable;
use Spryker\Zed\ProductLabelGui\Communication\Table\AvailableProductTable;
use Spryker\Zed\ProductLabelGui\Communication\Table\ProductLabelTable;
use Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductOverviewTable;
use Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilder;
use Spryker\Zed\ProductLabelGui\Communication\Tabs\ProductLabelFormTabs;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToStoreFacadeInterface;
use Spryker\Zed\ProductLabelGui\ProductLabelGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig getConfig()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiRepositoryInterface getRepository()
 */
class ProductLabelGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Table\ProductLabelTable
     */
    public function createProductLabelTable()
    {
        return new ProductLabelTable($this->getQueryContainer());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelAggregateFormTransfer $aggregateFormTransfer
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductLabelAggregateForm(ProductLabelAggregateFormTransfer $aggregateFormTransfer, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductLabelAggregateFormType::class, $aggregateFormTransfer, $options);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createUniqueProductLabelNameConstraint()
    {
        return new UniqueProductLabelNameConstraint([
            UniqueProductLabelNameConstraint::OPTION_QUERY_CONTAINER => $this->getQueryContainer(),
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createAddIdsProductToAssignConstraint(): Constraint
    {
        return new AddIdsProductToAssignConstraint([
            AddIdsProductToAssignConstraint::OPTION_PRODUCT_LABEL_FACADE => $this->getProductLabelFacade(),
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createAddIdsProductToDeAssignConstraint(): Constraint
    {
        return new AddIdsProductToDeAssignConstraint([
            AddIdsProductToDeAssignConstraint::OPTION_PRODUCT_LABEL_FACADE => $this->getProductLabelFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelAggregateFormDataProvider
     */
    public function createProductLabelAggregateFormDataProvider()
    {
        return new ProductLabelAggregateFormDataProvider(
            $this->createProductLabelFormDataProvider(),
            $this->createRelatedProductFormDataProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelFormDataProvider
     */
    public function createProductLabelFormDataProvider()
    {
        return new ProductLabelFormDataProvider(
            $this->getLocaleFacade(),
            $this->getProductLabelFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\RelatedProductFormDataProvider
     */
    public function createRelatedProductFormDataProvider()
    {
        return new RelatedProductFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelInterface
     */
    public function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createProductLabelFormTabs()
    {
        return new ProductLabelFormTabs();
    }

    /**
     * @param int|null $idProductLabel
     *
     * @return \Spryker\Zed\ProductLabelGui\Communication\Table\AvailableProductTable
     */
    public function createAvailableProductTable($idProductLabel = null)
    {
        return new AvailableProductTable(
            $this->createRelatedProductTableQueryBuilder(),
            $this->getLocaleFacade(),
            $this->getRepository(),
            $idProductLabel,
        );
    }

    /**
     * @param int|null $idProductLabel
     *
     * @return \Spryker\Zed\ProductLabelGui\Communication\Table\AssignedProductTable
     */
    public function createAssignedProductTable($idProductLabel = null)
    {
        return new AssignedProductTable(
            $this->createRelatedProductTableQueryBuilder(),
            $this->getLocaleFacade(),
            $this->getRepository(),
            $idProductLabel,
        );
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductOverviewTable
     */
    public function createRelatedProductOverviewTable($idProductLabel)
    {
        return new RelatedProductOverviewTable(
            $this->createRelatedProductTableQueryBuilder(),
            $this->getLocaleFacade(),
            $this->getRepository(),
            $idProductLabel,
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductLabelDeleteForm(): FormInterface
    {
        return $this->getFormFactory()->create(ProductLabelDeleteForm::class);
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilderInterface
     */
    public function createRelatedProductTableQueryBuilder()
    {
        return new RelatedProductTableQueryBuilder(
            $this->getProductQueryContainer(),
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\QueryContainer\ProductLabelGuiToProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface
     */
    public function getMoneyFacade()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductLabelGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_STORE);
    }
}
