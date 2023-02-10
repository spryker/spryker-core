<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\GuiTable\Http\DataRequest;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFiltersConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface;
use Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilder;
use Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilderInterface;
use SprykerTest\Shared\GuiTable\GuiTableSharedTester;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group GuiTable
 * @group Http
 * @group DataRequest
 * @group DataRequestBuilderTest
 * Add your own group annotations below this line
 */
class DataRequestBuilderTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_CONFIG_DEFAULT_PAGE_SIZE = 10;

    /**
     * @var string
     */
    protected const TEST_PARAM_SEARCH = 'search string';

    /**
     * @var string
     */
    protected const TEST_PARAM_SORT_BY = 'column1';

    /**
     * @var string
     */
    protected const TEST_PARAM_SORT_DIRECTION_ASC = 'ASC';

    /**
     * @var string
     */
    protected const TEST_PARAM_SORT_DIRECTION_DESC = 'DESC';

    /**
     * @var int
     */
    protected const TEST_PARAM_PAGE = 3;

    /**
     * @var int
     */
    protected const TEST_PARAM_PAGE_SIZE = 5;

    /**
     * @var array<string, string>
     */
    protected const TEST_PARAM_FILTERS = ['filterId' => 'filterValue'];

    /**
     * @var string
     */
    protected const TEST_TYPE_DATE = 'date';

    /**
     * @var string
     */
    protected const TEST_VALUE_DATE_FORMATTED = 'date_formatted';

    /**
     * @var \SprykerTest\Shared\GuiTable\GuiTableSharedTester
     */
    protected GuiTableSharedTester $tester;

    /**
     * @return void
     */
    public function testBuildGuiTableDataRequestFromRequestReturnsCorrectGuiTableDataRequestTransfer(): void
    {
        // Arrange
        $requestParams = [
            'search' => static::TEST_PARAM_SEARCH,
            'sortBy' => static::TEST_PARAM_SORT_BY,
            'sortDirection' => static::TEST_PARAM_SORT_DIRECTION_DESC,
            'page' => static::TEST_PARAM_PAGE,
            'pageSize' => static::TEST_PARAM_PAGE_SIZE,
            'filter' => json_encode(static::TEST_PARAM_FILTERS),
        ];
        $request = new Request($requestParams);
        $guiTableFiltersConfigurationTransfer = (new GuiTableFiltersConfigurationTransfer())
            ->addItem(
                (new GuiTableFilterTransfer())
                    ->setId('filterId')
                    ->setType(static::TEST_TYPE_DATE),
            );
        $guiTableConfigurationTransfer = (new GuiTableConfigurationTransfer())
            ->setFilters($guiTableFiltersConfigurationTransfer);

        // Act
        $guiTableDataRequestTransfer = $this->createDataRequestBuilder()
            ->buildGuiTableDataRequestFromRequest($request, $guiTableConfigurationTransfer);

        // Assert
        $this->assertSame(static::TEST_PARAM_SEARCH, $guiTableDataRequestTransfer->getSearchTerm());
        $this->assertSame(static::TEST_PARAM_SORT_BY, $guiTableDataRequestTransfer->getOrderBy());
        $this->assertSame(static::TEST_PARAM_SORT_DIRECTION_DESC, $guiTableDataRequestTransfer->getOrderDirection());
        $this->assertSame(static::TEST_PARAM_PAGE, $guiTableDataRequestTransfer->getPage());
        $this->assertSame(static::TEST_PARAM_PAGE_SIZE, $guiTableDataRequestTransfer->getPageSize());
        $this->assertCount(1, $guiTableDataRequestTransfer->getFilters());
        $this->assertSame(static::TEST_PARAM_FILTERS, $guiTableDataRequestTransfer->getFilters());
    }

    /**
     * @return void
     */
    public function testBuildGuiTableDataRequestFromRequestReturnsGuiTableDataRequestTransferWithDefaultValues(): void
    {
        // Arrange
        $request = new Request([]);

        // Act
        $guiTableDataRequestTransfer = $this->createDataRequestBuilder()
            ->buildGuiTableDataRequestFromRequest($request, new GuiTableConfigurationTransfer());

        // Assert
        $this->assertIsArray($guiTableDataRequestTransfer->getFilters());
        $this->assertCount(0, $guiTableDataRequestTransfer->getFilters());
        $this->assertNull($guiTableDataRequestTransfer->getOrderDirection());
        $this->assertSame(1, $guiTableDataRequestTransfer->getPage());
        $this->assertSame(static::TEST_CONFIG_DEFAULT_PAGE_SIZE, $guiTableDataRequestTransfer->getPageSize());
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilderInterface
     */
    protected function createDataRequestBuilder(): DataRequestBuilderInterface
    {
        return new DataRequestBuilder(
            $this->createGuiTableToUtilEncodingServiceBridgeMock(),
            $this->createGuiTableConfigMock(),
            $this->tester->createDateRangeRequestFilterValueNormalizer(),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface
     */
    protected function createGuiTableToUtilEncodingServiceBridgeMock(): GuiTableToUtilEncodingServiceInterface
    {
        $guiTableToUtilEncodingServiceBridgeMock = $this->getMockBuilder(GuiTableToUtilEncodingServiceInterface::class)->getMock();
        $guiTableToUtilEncodingServiceBridgeMock
            ->method('decodeJson')
            ->willReturnCallback(function (string $json): array {
                return json_decode($json, true);
            });

        return $guiTableToUtilEncodingServiceBridgeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface
     */
    protected function createGuiTableConfigMock(): GuiTableConfigInterface
    {
        $guiTableConfigMock = $this->getMockBuilder(GuiTableConfigInterface::class)->getMock();
        $guiTableConfigMock->method('getDefaultPageSize')->willReturn(static::TEST_CONFIG_DEFAULT_PAGE_SIZE);

        return $guiTableConfigMock;
    }
}
