<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Codeception\Test\Unit;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertion;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group ProductConcreteAssertionTest
 * Add your own group annotations below this line
 */
class ProductConcreteAssertionTest extends Unit
{
    public const SKU = 'sku-concrete';
    public const ID_PRODUCT_CONCRETE = 1;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productQueryContainer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->productQueryContainer = $this->getMockBuilder(ProductQueryContainerInterface::class)
            ->disableOriginalConstructor()->getMock();
    }

    /**
     * @return void
     */
    public function testAssertSkuIsUnique()
    {
        $query = $this->getMockBuilder(SpyProductQuery::class)
            ->disableOriginalConstructor()->getMock();

        $query
            ->expects($this->once())
            ->method('count')
            ->willReturn(0);

        $this->productQueryContainer
            ->expects($this->once())
            ->method('queryProductConcreteBySku')
            ->with(self::SKU)
            ->willReturn($query);

        $productConcreteAssertion = new ProductConcreteAssertion($this->productQueryContainer);

        $productConcreteAssertion->assertSkuIsUnique(self::SKU);
    }

    /**
     * @return void
     */
    public function testAssertSkuIsUniqueShouldThrowException()
    {
        $this->expectException(ProductConcreteExistsException::class);
        $this->expectExceptionMessage(sprintf(
            'Product concrete with sku %s already exists',
            self::SKU
        ));

        $query = $this->getMockBuilder(SpyProductQuery::class)
            ->disableOriginalConstructor()->getMock();

        $query
            ->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $this->productQueryContainer
            ->expects($this->once())
            ->method('queryProductConcreteBySku')
            ->with(self::SKU)
            ->willReturn($query);

        $productConcreteAssertion = new ProductConcreteAssertion($this->productQueryContainer);

        $productConcreteAssertion->assertSkuIsUnique(self::SKU);
    }

    /**
     * @return void
     */
    public function testAssertSkuIsUniqueWhenUpdatingProduct()
    {
        $query = $this->getMockBuilder(SpyProductQuery::class)
            ->disableOriginalConstructor()->getMock();

        $query
            ->expects($this->at(1))
            ->method('count')
            ->willReturn(0);

        $query
            ->expects($this->at(0))
            ->method('filterByIdProduct')
            ->with(self::ID_PRODUCT_CONCRETE, Criteria::NOT_EQUAL)
            ->willReturn($query);

        $this->productQueryContainer
            ->expects($this->once())
            ->method('queryProductConcreteBySku')
            ->with(self::SKU)
            ->willReturn($query);

        $productConcreteAssertion = new ProductConcreteAssertion($this->productQueryContainer);

        $productConcreteAssertion->assertSkuIsUniqueWhenUpdatingProduct(self::ID_PRODUCT_CONCRETE, self::SKU);
    }

    /**
     * @return void
     */
    public function testAssertSkuIsUniqueWhenUpdatingProductShouldThrowException()
    {
        $this->expectException(ProductConcreteExistsException::class);
        $this->expectExceptionMessage(sprintf(
            'Product concrete with sku %s already exists',
            self::SKU
        ));

        $query = $this->getMockBuilder(SpyProductQuery::class)
            ->disableOriginalConstructor()->getMock();

        $query
            ->expects($this->at(1))
            ->method('count')
            ->willReturn(1);

        $query
            ->expects($this->at(0))
            ->method('filterByIdProduct')
            ->with(self::ID_PRODUCT_CONCRETE, Criteria::NOT_EQUAL)
            ->willReturn($query);

        $this->productQueryContainer
            ->expects($this->once())
            ->method('queryProductConcreteBySku')
            ->with(self::SKU)
            ->willReturn($query);

        $productConcreteAssertion = new ProductConcreteAssertion($this->productQueryContainer);

        $productConcreteAssertion->assertSkuIsUniqueWhenUpdatingProduct(self::ID_PRODUCT_CONCRETE, self::SKU);
    }

    /**
     * @return void
     */
    public function testAssertProductExists()
    {
        $query = $this->getMockBuilder(SpyProductQuery::class)
            ->disableOriginalConstructor()->getMock();

        $query
            ->expects($this->at(1))
            ->method('count')
            ->willReturn(1);

        $query
            ->expects($this->at(0))
            ->method('filterByIdProduct')
            ->with(self::ID_PRODUCT_CONCRETE)
            ->willReturn($query);

        $this->productQueryContainer
            ->expects($this->once())
            ->method('queryProduct')
            ->willReturn($query);

        $productConcreteAssertion = new ProductConcreteAssertion($this->productQueryContainer);

        $productConcreteAssertion->assertProductExists(self::ID_PRODUCT_CONCRETE);
    }

    /**
     * @return void
     */
    public function testAssertProductExistsShouldThrowException()
    {
        $this->expectException(MissingProductException::class);
        $this->expectExceptionMessage(sprintf(
            'Product concrete with id "%s" does not exist.',
            self::ID_PRODUCT_CONCRETE
        ));

        $query = $this->getMockBuilder(SpyProductQuery::class)
            ->disableOriginalConstructor()->getMock();

        $query
            ->expects($this->at(1))
            ->method('count')
            ->willReturn(0);

        $query
            ->expects($this->at(0))
            ->method('filterByIdProduct')
            ->with(self::ID_PRODUCT_CONCRETE)
            ->willReturn($query);

        $this->productQueryContainer
            ->expects($this->once())
            ->method('queryProduct')
            ->willReturn($query);

        $productConcreteAssertion = new ProductConcreteAssertion($this->productQueryContainer);

        $productConcreteAssertion->assertProductExists(self::ID_PRODUCT_CONCRETE);
    }
}
