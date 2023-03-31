<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Communication\Table;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Spryker\Zed\Currency\CurrencyDependencyProvider;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;
use Spryker\Zed\ProductOption\Persistence\ProductOptionRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Communication
 * @group Table
 * @group ProductOptionListTableTest
 * Add your own group annotations below this line
 */
class ProductOptionListTableTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_OPTION_VALUE_SKU_1 = 'PRODUCT_OPTION_VALUE_SKU_1';

    /**
     * @var string
     */
    protected const PRODUCT_OPTION_VALUE_SKU_2 = 'PRODUCT_OPTION_VALUE_SKU_2';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @var \SprykerTest\Zed\ProductOption\ProductOptionCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testFetchDataShouldReturnProductOptionGroupsGroupedByProductOptionGroupId(): void
    {
        // Arrange
        $productOptionGroup = $this->tester->haveProductOptionGroup();
        $idProductOptionGroup = $productOptionGroup->getIdProductOptionGroup();

        $this->tester->createProductOptionValueEntity(static::PRODUCT_OPTION_VALUE_SKU_1, $idProductOptionGroup);
        $this->tester->createProductOptionValueEntity(static::PRODUCT_OPTION_VALUE_SKU_2, $idProductOptionGroup);

        $expectedCount = 1;

        $this->tester->setDependency(CurrencyDependencyProvider::FACADE_STORE, $this->createCurrencyToStoreFacadeMock());

        // Act
        $result = $this->getProductOptionListTableMock()->fetchData();

        // Assert
        $resultProductOptionGroupIds = array_column($result, SpyProductOptionGroupTableMap::COL_ID_PRODUCT_OPTION_GROUP);
        $this->assertNotEmpty($result);
        $this->assertContains($idProductOptionGroup, $resultProductOptionGroupIds);

        $filteredProductOptionGroupIds = array_filter($resultProductOptionGroupIds, function ($productGroupId) use ($idProductOptionGroup) {
            return $productGroupId === $idProductOptionGroup;
        });
        $this->assertSame($expectedCount, count($filteredProductOptionGroupIds));
    }

    /**
     * @return \SprykerTest\Zed\ProductOption\Communication\Table\ProductOptionListTableMock
     */
    protected function getProductOptionListTableMock(): ProductOptionListTableMock
    {
        $productOptionQueryContainer = new ProductOptionQueryContainer();
        $productOptionRepository = new ProductOptionRepository();

        return new ProductOptionListTableMock(
            $productOptionQueryContainer,
            $this->getProductOptionToCurrencyFacadeMock(),
            $this->getProductOptionToMoneyFacadeMock(),
            $productOptionRepository,
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface
     */
    protected function getProductOptionToCurrencyFacadeMock(): ProductOptionToCurrencyFacadeInterface
    {
        $productOptionToCurrencyFacadeMock = $this->getMockBuilder(ProductOptionToCurrencyFacadeBridge::class)
            ->onlyMethods(['getByIdCurrency'])
            ->disableOriginalConstructor()
            ->getMock();

        $currentCurrency = $this->tester->getLocator()
            ->currency()
            ->facade()
            ->getCurrent();

        $productOptionToCurrencyFacadeMock->expects($this->any())
            ->method('getByIdCurrency')
            ->willReturn($currentCurrency);

        return $productOptionToCurrencyFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyFacadeInterface
     */
    protected function getProductOptionToMoneyFacadeMock(): ProductOptionToMoneyFacadeInterface
    {
        return $this->getMockBuilder(ProductOptionToMoneyFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface
     */
    protected function createCurrencyToStoreFacadeMock(): CurrencyToStoreFacadeInterface
    {
        $currencyToStoreFacadeMock = $this->createMock(CurrencyToStoreFacadeInterface::class);
        $currencyToStoreFacadeMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())
                ->setName(static::DEFAULT_STORE)
                ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY));

        return $currencyToStoreFacadeMock;
    }
}
