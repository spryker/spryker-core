<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductRelationGui\Communication\FilterProvider\FilterProvider;
use Spryker\Zed\ProductRelationGui\Communication\FilterProvider\FilterProviderInterface;
use Spryker\Zed\ProductRelationGui\Communication\Form\Constraint\UniqueRelationTypeForProductAbstract;
use Spryker\Zed\ProductRelationGui\Communication\Form\DataProvider\ProductRelationTypeDataProvider;
use Spryker\Zed\ProductRelationGui\Communication\Form\ProductRelationFormType;
use Spryker\Zed\ProductRelationGui\Communication\Form\Transformer\RuleQuerySetTransformer;
use Spryker\Zed\ProductRelationGui\Communication\Table\ProductRelationTable;
use Spryker\Zed\ProductRelationGui\Communication\Table\ProductRuleTable;
use Spryker\Zed\ProductRelationGui\Communication\Table\ProductTable;
use Spryker\Zed\ProductRelationGui\Communication\Tabs\ProductRelationTabs;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductAttributeFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToProductRelationQueryContainerInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductRelationGui\ProductRelationGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ProductRelationGui\ProductRelationGuiConfig getConfig()
 */
class ProductRelationGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createRuleSetTransformer(): DataTransformerInterface
    {
        return new RuleQuerySetTransformer($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Communication\Form\DataProvider\ProductRelationTypeDataProvider
     */
    public function createProductRelationFormTypeDataProvider(): ProductRelationTypeDataProvider
    {
        return new ProductRelationTypeDataProvider($this->getProductRelationFacade());
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createUniqueRelationTypeForProductAbstractConstraint(): Constraint
    {
        return new UniqueRelationTypeForProductAbstract([
            UniqueRelationTypeForProductAbstract::OPTION_PRODUCT_RELATION_FACADE => $this->getProductRelationFacade(),
        ]);
    }

    /**
     * @param \Spryker\Zed\ProductRelationGui\Communication\Form\DataProvider\ProductRelationTypeDataProvider $productRelationFormTypeDataProvider
     * @param int|null $idProductRelation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRelationForm(
        ProductRelationTypeDataProvider $productRelationFormTypeDataProvider,
        ?int $idProductRelation = null
    ): FormInterface {
        return $this->getFormFactory()->create(
            ProductRelationFormType::class,
            $productRelationFormTypeDataProvider->getData($idProductRelation),
            $productRelationFormTypeDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Communication\Tabs\ProductRelationTabs
     */
    public function createProductRelationTabs(): ProductRelationTabs
    {
        return new ProductRelationTabs();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Spryker\Zed\ProductRelationGui\Communication\Table\ProductRuleTable
     */
    public function createProductRuleTable(ProductRelationTransfer $productRelationTransfer): ProductRuleTable
    {
        return new ProductRuleTable(
            $this->getProductFacade(),
            $this->getProductRelationQueryContainer(),
            $this->getUtilEncodingService(),
            $this->getLocaleFacade(),
            $this->getConfig(),
            $productRelationTransfer
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Communication\Table\ProductRelationTable
     */
    public function createProductRelationTable(): ProductRelationTable
    {
        return new ProductRelationTable(
            $this->getProductRelationPropelQuery(),
            $this->getProductFacade(),
            $this->getConfig(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param int|null $idProductRelation
     *
     * @return \Spryker\Zed\ProductRelationGui\Communication\Table\ProductTable
     */
    public function createProductTable(?int $idProductRelation = null): ProductTable
    {
        return new ProductTable(
            $this->getProductAbstractPropelQuery(),
            $this->getLocaleFacade(),
            $this->getUtilEncodingService(),
            $this->getMoneyFacade(),
            $this->getPriceProductFacade(),
            $idProductRelation
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Communication\FilterProvider\FilterProviderInterface
     */
    public function createFilterProvider(): FilterProviderInterface
    {
        return new FilterProvider($this->getProductAttributeFacade());
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeInterface
     */
    public function getProductRelationFacade(): ProductRelationGuiToProductRelationFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::FACADE_PRODUCT_RELATION);
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductRelationGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductRelationGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductRelationGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToProductRelationQueryContainerInterface
     */
    public function getProductRelationQueryContainer(): ProductRelationGuiToProductRelationQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_RELATION);
    }

    /**
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function getProductRelationPropelQuery(): SpyProductRelationQuery
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::PROPEL_QUERY_PRODUCT_RELATION);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractPropelQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function getProductAAttributeKeyPropelQuery(): SpyProductAttributeKeyQuery
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::PROPEL_QUERY_PRODUCT_ATTRIBUTE_KEY);
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): ProductRelationGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): ProductRelationGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductAttributeFacadeInterface
     */
    public function getProductAttributeFacade(): ProductRelationGuiToProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationGuiDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }
}
