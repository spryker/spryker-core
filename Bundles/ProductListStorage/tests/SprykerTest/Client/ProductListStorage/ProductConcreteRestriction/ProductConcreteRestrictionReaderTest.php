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
     * @var \SprykerTest\Client\ProductListStorage\ProductListStorageClientTester;
     */
    protected $tester;

    /**
     * @return array
     */
    public function getProductConcreteRestrictionReaderData(): array
    {
        return [
            'test product not in product list' => [
                [
                    'whitelist' => [],
                    'blacklist' => [],
                ],
                [
                    'whitelist' => [],
                    'blacklist' => [],
                ],
                false,
            ],
            'test product blacklisted' => [
                [
                    'whitelist' => [],
                    'blacklist' => [1],
                ],
                [
                    'whitelist' => [],
                    'blacklist' => [1],
                ],
                true,
            ],
            'test product whitelisted' => [
                [
                    'whitelist' => [1],
                    'blacklist' => [],
                ],
                [
                    'whitelist' => [1],
                    'blacklist' => [],
                ],
                false,
            ],
            'test product in blacklist and not in whitelist' => [
                [
                    'whitelist' => [2],
                    'blacklist' => [1],
                ],
                [
                    'whitelist' => [],
                    'blacklist' => [1],
                ],
                true,
            ],
            'test product not in whitelist' => [
                [
                    'whitelist' => [2],
                    'blacklist' => [],
                ],
                [
                    'whitelist' => [],
                    'blacklist' => [],
                ],
                true,
            ],
            'test product whitelisted and blacklisted' => [
                [
                    'whitelist' => [3],
                    'blacklist' => [2],
                ],
                [
                    'whitelist' => [3],
                    'blacklist' => [2],
                ],
                true,
            ],
        ];
    }

    /**
     * @dataProvider getProductConcreteRestrictionReaderData
     *
     * @param array $customerData
     * @param array $productData
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testProductConcreteRestrictionReader(array $customerData, array $productData, bool $expectedResult)
    {
        $customerClientMock = $this->createCustomerClientMock($customerData['whitelist'], $customerData['blacklist']);
        $productListProductConcreteStorageReader = $this->createProductListProductConcreteStorageReader(
            $productData['whitelist'],
            $productData['blacklist']
        );

        $productConcreteRestrictionReader = new ProductConcreteRestrictionReader(
            $customerClientMock,
            $productListProductConcreteStorageReader
        );

        $actualResult = $productConcreteRestrictionReader->isProductConcreteRestricted(self::CONCRETE_PRODUCT_ID);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param int[] $whiteListIds
     * @param int[] $blackListIds
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
     */
    protected function createCustomerClientMock(array $whiteListIds, array $blackListIds)
    {
        $customerProductListCollectionTransfer = new CustomerProductListCollectionTransfer();
        $customerProductListCollectionTransfer->setWhitelistIds($whiteListIds);
        $customerProductListCollectionTransfer->setBlacklistIds($blackListIds);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setCustomerProductListCollection($customerProductListCollectionTransfer);

        $customerClientMock = $this->getMockBuilder(ProductListStorageToCustomerClientInterface::class)->getMock();
        $customerClientMock->method('getCustomer')->willReturn($customerTransfer);

        return $customerClientMock;
    }

    /**
     * @param int[] $whiteListIds
     * @param int[] $blackListIds
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader;
     */
    protected function createProductListProductConcreteStorageReader(array $whiteListIds, array $blackListIds)
    {
        $productConcreteProductListStorageTransfer = new ProductConcreteProductListStorageTransfer();
        $productConcreteProductListStorageTransfer->setIdWhitelists($whiteListIds);
        $productConcreteProductListStorageTransfer->setIdBlacklists($blackListIds);

        $productListProductConcreteStorageReader = $this->getMockBuilder(ProductListProductConcreteStorageReaderInterface::class)->getMock();
        $productListProductConcreteStorageReader->expects($this->once())->method('findProductConcreteProductListStorage')->willReturn($productConcreteProductListStorageTransfer);

        return $productListProductConcreteStorageReader;
    }
}
