<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductListStorage\ProductConcreteRestriction;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReader;
use Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ProductListStorage
 * @group ProductConcreteRestriction
 * @group ProductConcreteRestrictionReaderTest
 * Add your own group annotations below this line
 */
class ProductConcreteRestrictionReaderTest extends Unit
{
    public const CONCRETE_PRODUCT_ID = 1;

    /**
     * @var \SprykerTest\Client\ProductListStorage\ProductConcreteRestrictionReaderTester;
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductNotInProductList()
    {
        $customerWhiteListIds = [];
        $customerBlackListIds = [];

        $productWhiteListids = [];
        $productBlackListIds = [];

        $expectedResult = false;

        $customerClientMock = $this->generateCustomerClientMock($customerWhiteListIds, $customerBlackListIds);
        $productListProductConcreteStorageReader = $this->generateProductListProductConcreteStorageReader($productWhiteListids, $productBlackListIds);

        $productConcreteRestrictionReader = new ProductConcreteRestrictionReader($customerClientMock, $productListProductConcreteStorageReader);

        $actualResult = $productConcreteRestrictionReader->isProductConcreteRestricted(self::CONCRETE_PRODUCT_ID);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testProductBlacklisted()
    {
        $customerWhiteListIds = [];
        $customerBlackListIds = [1];

        $productWhiteListids = [];
        $productBlackListIds = [1];

        $expectedResult = true;

        $customerClientMock = $this->generateCustomerClientMock($customerWhiteListIds, $customerBlackListIds);
        $productListProductConcreteStorageReader = $this->generateProductListProductConcreteStorageReader($productWhiteListids, $productBlackListIds);

        $productConcreteRestrictionReader = new ProductConcreteRestrictionReader($customerClientMock, $productListProductConcreteStorageReader);

        $actualResult = $productConcreteRestrictionReader->isProductConcreteRestricted(self::CONCRETE_PRODUCT_ID);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testProductWhitelisted()
    {
        $customerWhiteListIds = [1];
        $customerBlackListIds = [];

        $productWhiteListids = [1];
        $productBlackListIds = [];

        $expectedResult = false;

        $customerClientMock = $this->generateCustomerClientMock($customerWhiteListIds, $customerBlackListIds);
        $productListProductConcreteStorageReader = $this->generateProductListProductConcreteStorageReader($productWhiteListids, $productBlackListIds);

        $productConcreteRestrictionReader = new ProductConcreteRestrictionReader($customerClientMock, $productListProductConcreteStorageReader);

        $actualResult = $productConcreteRestrictionReader->isProductConcreteRestricted(self::CONCRETE_PRODUCT_ID);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testProductInBlackListAndNotInWhiteList()
    {
        $customerWhiteListIds = [2];
        $customerBlackListIds = [1];

        $productWhiteListids = [];
        $productBlackListIds = [1];

        $expectedResult = true;

        $customerClientMock = $this->generateCustomerClientMock($customerWhiteListIds, $customerBlackListIds);
        $productListProductConcreteStorageReader = $this->generateProductListProductConcreteStorageReader($productWhiteListids, $productBlackListIds);

        $productConcreteRestrictionReader = new ProductConcreteRestrictionReader($customerClientMock, $productListProductConcreteStorageReader);

        $actualResult = $productConcreteRestrictionReader->isProductConcreteRestricted(self::CONCRETE_PRODUCT_ID);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testProductNotInWhiteList()
    {
        $customerWhiteListIds = [2];
        $customerBlackListIds = [];

        $productWhiteListids = [];
        $productBlackListIds = [];

        $expectedResult = true;

        $customerClientMock = $this->generateCustomerClientMock($customerWhiteListIds, $customerBlackListIds);
        $productListProductConcreteStorageReader = $this->generateProductListProductConcreteStorageReader($productWhiteListids, $productBlackListIds);

        $productConcreteRestrictionReader = new ProductConcreteRestrictionReader($customerClientMock, $productListProductConcreteStorageReader);

        $actualResult = $productConcreteRestrictionReader->isProductConcreteRestricted(self::CONCRETE_PRODUCT_ID);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testProductWhitelistedAndBlacklisted()
    {
        $customerWhiteListIds = [3];
        $customerBlackListIds = [2];

        $productWhiteListids = [3];
        $productBlackListIds = [2];

        $expectedResult = true;

        $customerClientMock = $this->generateCustomerClientMock($customerWhiteListIds, $customerBlackListIds);
        $productListProductConcreteStorageReader = $this->generateProductListProductConcreteStorageReader($productWhiteListids, $productBlackListIds);

        $productConcreteRestrictionReader = new ProductConcreteRestrictionReader($customerClientMock, $productListProductConcreteStorageReader);

        $actualResult = $productConcreteRestrictionReader->isProductConcreteRestricted(self::CONCRETE_PRODUCT_ID);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param array $whiteListIds
     * @param array $blackListIds
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
     */
    protected function generateCustomerClientMock(array $whiteListIds, array $blackListIds)
    {
        $customerProductListCollectionTransfer = new CustomerProductListCollectionTransfer();
        $customerProductListCollectionTransfer->setWhitelistIds($whiteListIds);
        $customerProductListCollectionTransfer->setBlacklistIds($blackListIds);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setCustomerProductListCollection($customerProductListCollectionTransfer);

        $customerClientMock = $this->createCustomerClientMock();
        $customerClientMock->method('getCustomer')->willReturn($customerTransfer);

        return $customerClientMock;
    }

    /**
     * @param array $whiteListIds
     * @param array $blackListIds
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader;
     */
    protected function generateProductListProductConcreteStorageReader(array $whiteListIds, array $blackListIds)
    {
        $productConcreteProductListStorageTransfer = new ProductConcreteProductListStorageTransfer();
        $productConcreteProductListStorageTransfer->setIdWhitelists($whiteListIds);
        $productConcreteProductListStorageTransfer->setIdBlacklists($blackListIds);

        $productListProductConcreteStorageReader = $this->createProductListProductConcreteStorageReader();
        $productListProductConcreteStorageReader->expects($this->once())->method('findProductConcreteProductListStorage')->willReturn($productConcreteProductListStorageTransfer);

        return $productListProductConcreteStorageReader;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
     */
    protected function createCustomerClientMock()
    {
        return $this->getMockBuilder(ProductListStorageToCustomerClientInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader;
     */
    protected function createProductListProductConcreteStorageReader()
    {
        return $this->getMockBuilder(ProductListProductConcreteStorageReaderInterface::class)->getMock();
    }
}
