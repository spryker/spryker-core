<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication;

use Generated\Shared\Transfer\ProductLabelAggregateFormTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\UniqueProductLabelNameConstraint;
use Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelAggregateFormDataProvider;
use Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelFormDataProvider;
use Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\RelatedProductFormDataProvider;
use Spryker\Zed\ProductLabelGui\Communication\Form\ProductLabelAggregateFormType;
use Spryker\Zed\ProductLabelGui\Communication\Form\ProductLabelFormType;
use Spryker\Zed\ProductLabelGui\Communication\Form\ProductLabelLocalizedAttributesFormType;
use Spryker\Zed\ProductLabelGui\Communication\Form\RelatedProductFormType;
use Spryker\Zed\ProductLabelGui\Communication\Table\AssignedProductTable;
use Spryker\Zed\ProductLabelGui\Communication\Table\AvailableProductTable;
use Spryker\Zed\ProductLabelGui\Communication\Table\ProductLabelTable;
use Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductOverviewTable;
use Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilder;
use Spryker\Zed\ProductLabelGui\Communication\Tabs\ProductLabelFormTabs;
use Spryker\Zed\ProductLabelGui\ProductLabelGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig getConfig()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelGui\Business\ProductLabelGuiFacadeInterface getFacade()
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
     * @deprecated Use `getProductLabelAggregateForm()` instead.
     *
     * @param \Generated\Shared\Transfer\ProductLabelAggregateFormTransfer $aggregateFormTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductLabelAggregateForm(
        ProductLabelAggregateFormTransfer $aggregateFormTransfer,
        array $options = []
    ) {
        return $this
            ->getFormFactory()
            ->create(
                $this->createProductLabelAggregateFormType(),
                $aggregateFormTransfer,
                $options
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelAggregateFormTransfer $aggregateFormTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductLabelAggregateForm(
        ProductLabelAggregateFormTransfer $aggregateFormTransfer,
        array $options = []
    ) {
        return $this->createProductLabelAggregateForm($aggregateFormTransfer, $options);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createProductLabelAggregateFormType()
    {
        return ProductLabelAggregateFormType::class;
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createProductLabelFormType()
    {
        return ProductLabelFormType::class;
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createProductLabelLocalizedAttributesFormType()
    {
        return ProductLabelLocalizedAttributesFormType::class;
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
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createRelatedProductFormType()
    {
        return RelatedProductFormType::class;
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelAggregateFormDataProvider
     */
    public function createProductLabelAggregateFormDataProvider()
    {
        return new ProductLabelAggregateFormDataProvider(
            $this->createProductLabelFormDataProvider(),
            $this->createRelatedProductFormDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelFormDataProvider
     */
    protected function createProductLabelFormDataProvider()
    {
        return new ProductLabelFormDataProvider(
            $this->getLocaleFacade(),
            $this->getProductLabelFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\RelatedProductFormDataProvider
     */
    protected function createRelatedProductFormDataProvider()
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
            $this->getMoneyFacade(),
            $idProductLabel,
            $this->getPriceProductFacade()
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
            $this->getMoneyFacade(),
            $idProductLabel,
            $this->getPriceProductFacade()
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
            $this->getMoneyFacade(),
            $idProductLabel,
            $this->getPriceProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilderInterface
     */
    protected function createRelatedProductTableQueryBuilder()
    {
        return new RelatedProductTableQueryBuilder(
            $this->getProductQueryContainer(),
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\QueryContainer\ProductLabelGuiToProductQueryContainerInterface
     */
    protected function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToPriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
