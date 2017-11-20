<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductRelation\Communication\Form\Constraint\UniqueRelationTypeForProductAbstract;
use Spryker\Zed\ProductRelation\Communication\Form\DataProvider\ProductRelationTypeDataProvider;
use Spryker\Zed\ProductRelation\Communication\Form\DataProvider\ProductRelationTypeDataProviderInterface;
use Spryker\Zed\ProductRelation\Communication\Form\ProductRelationFormType;
use Spryker\Zed\ProductRelation\Communication\Form\Transformer\RuleQuerySetTransformer;
use Spryker\Zed\ProductRelation\Communication\QueryBuilder\FilterProvider;
use Spryker\Zed\ProductRelation\Communication\Table\ProductRelationTable;
use Spryker\Zed\ProductRelation\Communication\Table\ProductRuleTable;
use Spryker\Zed\ProductRelation\Communication\Table\ProductTable;
use Spryker\Zed\ProductRelation\Communication\Tabs\ProductRelationTabs;
use Spryker\Zed\ProductRelation\ProductRelationDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductRelation\ProductRelationConfig getConfig()
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface getFacade()
 */
class ProductRelationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param int|null $idProductRelation
     *
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createProductTable($idProductRelation = null)
    {
        return new ProductTable(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->getUtilEncodingService(),
            $this->getMoneyFacade(),
            $idProductRelation
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createProductRuleTable(ProductRelationTransfer $productRelationTransfer)
    {
        return new ProductRuleTable(
            $this->getProductFacade(),
            $this->getQueryContainer(),
            $this->getUtilEncodingService(),
            $productRelationTransfer,
            $this->getLocaleFacade()->getCurrentLocale(),
            $this->getConfig()->findYvesHost()
        );
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createProductRelationTable()
    {
        return new ProductRelationTable(
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getConfig(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\AbstractTabs
     */
    public function createProductRelationTabs()
    {
        return new ProductRelationTabs();
    }

    /**
     * @param \Spryker\Zed\ProductRelation\Communication\Form\DataProvider\ProductRelationTypeDataProviderInterface $productRelationFormTypeDataProvider
     * @param int|null $idProductRelation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRelationForm(
        ProductRelationTypeDataProviderInterface $productRelationFormTypeDataProvider,
        $idProductRelation = null
    ) {
        $productRelationFormType = $this->createRelationFormType();

        return $this->getFormFactory()->create(
            $productRelationFormType,
            $productRelationFormTypeDataProvider->getData($idProductRelation),
            $productRelationFormTypeDataProvider->getOptions()
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createUniqueRelationTypeForProductAbstractConstraint()
    {
        return new UniqueRelationTypeForProductAbstract([
            UniqueRelationTypeForProductAbstract::OPTION_PRODUCT_RELATION_QUERY_CONTAINER => $this->getQueryContainer(),
        ]);
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Communication\QueryBuilder\FilterProviderInterface
     */
    public function createQueryBuilderFilterProvider()
    {
        return new FilterProvider($this->getQueryContainer());
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    protected function createRelationFormType()
    {
        return new ProductRelationFormType(
            $this->createRuleSetTransformer(),
            $this->createUniqueRelationTypeForProductAbstractConstraint()
        );
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    protected function createRuleSetTransformer()
    {
        return new RuleQuerySetTransformer($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Communication\Form\DataProvider\ProductRelationTypeDataProviderInterface
     */
    public function createProductRelationFormTypeDataProvider()
    {
        return new ProductRelationTypeDataProvider($this->getFacade());
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::FACADE_PRODUCT);
    }
}
