<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscountConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Product\Business\ProductBusinessFactory;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\ProductDiscountConnector\Business\ProductDiscountConnectorBusinessFactory;
use Spryker\Zed\ProductDiscountConnector\Business\ProductDiscountConnectorFacade;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductBridge;
use Spryker\Zed\ProductDiscountConnector\ProductDiscountConnectorDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductDiscountConnector
 * @group Business
 * @group Facade
 * @group ProductDiscountConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductDiscountConnectorFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testIsProductAttributeSatisfiedByShouldReturnTrueWhenAttributePresent()
    {
        $abstractProductEntity = $this->createAbstractProductWithAttributes();

        $productDiscountConnectorFacade = $this->createProductDiscountConnectorFacade();

        $quoteTransfer = $this->createQuoteTransfer($abstractProductEntity);

        $clauseTransfer = $this->createClauseTransfer('red');

        $isSatisfied = $productDiscountConnectorFacade->isProductAttributeSatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()[0],
            $clauseTransfer
        );

        $this->assertTrue($isSatisfied);
    }

    /**
     * @return void
     */
    public function testIsProductAttributeSatisfiedByShouldReturnFalseWhenAttributeIsNotSet()
    {
        $abstractProductEntity = $this->createAbstractProductWithAttributes();

        $productDiscountConnectorFacade = $this->createProductDiscountConnectorFacade();

        $quoteTransfer = $this->createQuoteTransfer($abstractProductEntity);

        $clauseTransfer = $this->createClauseTransfer('green');

        $isSatisfied = $productDiscountConnectorFacade->isProductAttributeSatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()[0],
            $clauseTransfer
        );

        $this->assertFalse($isSatisfied);
    }

    /**
     * @return void
     */
    public function testCollectByProductAttributeShouldCollectAllItemsMatchingAttribute()
    {
        $abstractProductEntity = $this->createAbstractProductWithAttributes();

        $productDiscountConnectorFacade = $this->createProductDiscountConnectorFacade();

        $quoteTransfer = $this->createQuoteTransfer($abstractProductEntity);
        $clauseTransfer = $this->createClauseTransfer('red');

        $collected = $productDiscountConnectorFacade->collectByProductAttribute($quoteTransfer, $clauseTransfer);

        $this->assertCount(1, $collected);
    }

    /**
     * @return void
     */
    public function testCollectByProductAttributeWhenNoItemsMatchedShouldReturnEmptySet()
    {
        $abstractProductEntity = $this->createAbstractProductWithAttributes();

        $productDiscountConnectorFacade = $this->createProductDiscountConnectorFacade();

        $quoteTransfer = $this->createQuoteTransfer($abstractProductEntity);
        $clauseTransfer = $this->createClauseTransfer('green');

        $collected = $productDiscountConnectorFacade->collectByProductAttribute($quoteTransfer, $clauseTransfer);

        $this->assertCount(0, $collected);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createAbstractProductWithAttributes()
    {
        $taxSet = new SpyTaxSet();
        $taxSet->setName('DEFAULT');
        $taxSet->save();

        $taxSetRate = new SpyTaxRate();
        $taxSetRate->setFkCountry(60);
        $taxSetRate->setName('Test');

        $taxSetTaxTax = new SpyTaxSetTax();
        $taxSetTaxTax->setFkTaxRate($taxSetRate->getIdTaxRate());
        $taxSetTaxTax->setFkTaxSet($taxSet->getIdTaxSet());

        $attributes = ['color' => 'red', 'size' => 'medium'];
        $abstractSku = 'discount-attribute-test-sku';
        $attributesSerialized = json_encode($attributes);

        $abstractProductEntity = new SpyProductAbstract();
        $abstractProductEntity->setAttributes($attributesSerialized);
        $abstractProductEntity->setSku($abstractSku);
        $abstractProductEntity->setFkTaxSet($taxSetRate->getIdTaxRate());
        $abstractProductEntity->save();

        $abstractLocalizedProductAttributesEntity = new SpyProductAbstractLocalizedAttributes();
        $abstractLocalizedProductAttributesEntity->setFkProductAbstract($abstractProductEntity->getIdProductAbstract());
        $abstractLocalizedProductAttributesEntity->setAttributes($attributesSerialized);
        $abstractLocalizedProductAttributesEntity->setName('test product');
        $abstractLocalizedProductAttributesEntity->setFkLocale(66);
        $abstractLocalizedProductAttributesEntity->save();

        $concreteProductEntity = new SpyProduct();
        $concreteProductEntity->setAttributes($attributesSerialized);
        $concreteProductEntity->setFkProductAbstract($abstractProductEntity->getIdProductAbstract());
        $concreteProductEntity->setSku($abstractSku . '-2');
        $concreteProductEntity->save();

        $concreteLocalizedAttributesEntity = new SpyProductLocalizedAttributes();
        $concreteLocalizedAttributesEntity->setAttributes($attributesSerialized);
        $concreteLocalizedAttributesEntity->setFkLocale(66);
        $concreteLocalizedAttributesEntity->setFkProduct($concreteProductEntity->getIdProduct());
        $concreteLocalizedAttributesEntity->setName('test product concrete');
        $concreteLocalizedAttributesEntity->save();

        return $abstractProductEntity;
    }

    /**
     * @return \Spryker\Zed\ProductDiscountConnector\Business\ProductDiscountConnectorFacade
     */
    protected function createProductDiscountConnectorFacade()
    {
        $productDiscountConnectorFacade = new ProductDiscountConnectorFacade();

        $container = new Container();
        $dependencyProvider = new ProductDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyProvider->provideCommunicationLayerDependencies($container);
        $dependencyProvider->providePersistenceLayerDependencies($container);
        $productBusinessFactory = new ProductBusinessFactory();
        $productBusinessFactory->setContainer($container);
        $productFacade = new ProductFacade();
        $productFacade->setFactory($productBusinessFactory);

        $container = new Container();
        $dependencyProvider = new ProductDiscountConnectorDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyProvider->provideCommunicationLayerDependencies($container);
        $dependencyProvider->providePersistenceLayerDependencies($container);
        $container[ProductDiscountConnectorDependencyProvider::FACADE_PRODUCT] = function (Container $container) use ($productFacade) {
            return new ProductDiscountConnectorToProductBridge($productFacade);
        };

        $productCartConnectorBusinessFactory = new ProductDiscountConnectorBusinessFactory();
        $productCartConnectorBusinessFactory->setContainer($container);

        $productDiscountConnectorFacade->setFactory($productCartConnectorBusinessFactory);

        return $productDiscountConnectorFacade;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(SpyProductAbstract $abstractProductEntity)
    {
        $quoteTransfer = new QuoteTransfer();

        foreach ($abstractProductEntity->getSpyProducts() as $productEntity) {
            $itemTransfer = new ItemTransfer();
            $itemTransfer->setId($productEntity->getIdProduct());
            $quoteTransfer->addItem($itemTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function createClauseTransfer($value)
    {
        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator('=');
        $clauseTransfer->setAttribute('color');
        $clauseTransfer->setValue($value);
        $clauseTransfer->setAcceptedTypes([
            ComparatorOperators::TYPE_STRING,
        ]);

        return $clauseTransfer;
    }
}
