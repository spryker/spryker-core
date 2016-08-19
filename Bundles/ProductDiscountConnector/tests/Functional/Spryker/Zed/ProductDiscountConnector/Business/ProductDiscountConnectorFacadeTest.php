<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductDiscountConnector\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\ProductDiscountConnector\Business\ProductDiscountConnectorFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductDiscountConnector
 * @group Business
 * @group ProductDiscountConnectorFacadeTest
 */
class ProductDiscountConnectorFacadeTest extends Test
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
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createAbstractProductWithAttributes()
    {
        $attributes = ['color' => 'red', 'size' => 'medium'];
        $abstractSku = 'discount-attribute-test-sku';
        $attributesSerialized = json_encode($attributes);

        $abstractProductEntity = new SpyProductAbstract();
        $abstractProductEntity->setAttributes($attributesSerialized);
        $abstractProductEntity->setSku($abstractSku);
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
        return new ProductDiscountConnectorFacade();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(SpyProductAbstract $abstractProductEntity)
    {
        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setAbstractSku($abstractProductEntity->getSku());
        $quoteTransfer->addItem($itemTransfer);

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
            ComparatorOperators::TYPE_STRING
        ]);
        return $clauseTransfer;
    }

}
