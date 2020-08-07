<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GuiTable\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableFiltersConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\GuiTable\Communication\Plugin\GuiTable\DateRangeRequestFilterValueNormalizerPlugin;
use Spryker\Zed\GuiTable\Communication\Plugin\GuiTable\DateResponseColumnValueFormatterPlugin;
use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToLocaleFacadeBridge;
use Spryker\Zed\GuiTable\GuiTableDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GuiTable
 * @group Business
 * @group Facade
 * @group GuiTableFacadeTest
 * Add your own group annotations below this line
 */
class GuiTableFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\GuiTable\Business\ResponseFormatter\DataResponseFormatter::KEY_DATA_RESPONSE_ARRAY_DATA
     */
    protected const KEY_DATA_RESPONSE_ARRAY_DATA = 'data';

    protected const TEST_PARAM_SEARCH = 'search string';
    protected const TEST_PARAM_SORT_BY = 'column1';
    protected const TEST_PARAM_SORT_DIRECTION_ASC = 'ASC';
    protected const TEST_PARAM_SORT_DIRECTION_DESC = 'DESC';
    protected const TEST_PARAM_PAGE = 3;
    protected const TEST_PARAM_PAGE_SIZE = 5;
    protected const TEST_PARAM_TOTAL = 100;
    protected const TEST_DATE_FROM = '2020-05-31T21:00:00.437Z';
    protected const TEST_DATE_TO = '2020-06-18T20:59:59.027Z';
    protected const TEST_PARAM_FILTER = ['filterId' => 'filterValue'];
    protected const TEST_ID_LOCALE = 1;

    protected const TEST_COLUMN_ID_1 = 'columnId1';
    protected const TEST_COLUMN_ID_2 = 'columnId2';
    protected const TEST_TABLE_DATA = [
        [self::TEST_COLUMN_ID_1 => 'value1', self::TEST_COLUMN_ID_2 => 'value1'],
        [self::TEST_COLUMN_ID_1 => 'value2', self::TEST_COLUMN_ID_2 => 'value2'],
    ];
    protected const TEST_TYPE_DATE = 'date';
    protected const TEST_VALUE_DATE_FORMATTED = 'date_formatted';

    /**
     * @var \SprykerTest\Zed\GuiTable\GuiTableBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildGuiTableDataRequestReturnsCorrectGuiTableDataRequestTransfer(): void
    {
        // Arrange
        $this->setLocaleFacadeMockDependency();
        $this->tester->setDependency(GuiTableDependencyProvider::PLUGINS_REQUEST_FILTER_VALUE_NORMALIZER, []);
        $requestParams = [
            'search' => static::TEST_PARAM_SEARCH,
            'sortBy' => static::TEST_PARAM_SORT_BY,
            'sortDirection' => static::TEST_PARAM_SORT_DIRECTION_DESC,
            'page' => static::TEST_PARAM_PAGE,
            'pageSize' => static::TEST_PARAM_PAGE_SIZE,
            'filter' => json_encode(static::TEST_PARAM_FILTER),
        ];
        $guiTableFiltersConfigurationTransfer = (new GuiTableFiltersConfigurationTransfer())
            ->addItem(
                (new GuiTableFilterTransfer())
                    ->setId('filterId')
                    ->setType(static::TEST_TYPE_DATE)
            );
        $guiTableConfigurationTransfer = (new GuiTableConfigurationTransfer())
            ->setFilters($guiTableFiltersConfigurationTransfer);

        // Act
        $guiTableDataRequestTransfer = $this->tester->getFacade()->buildGuiTableDataRequest(
            $requestParams,
            $guiTableConfigurationTransfer
        );

        // Assert
        $this->assertEquals(static::TEST_PARAM_SEARCH, $guiTableDataRequestTransfer->getSearchTerm());
        $this->assertEquals(static::TEST_PARAM_SORT_BY, $guiTableDataRequestTransfer->getOrderBy());
        $this->assertEquals(static::TEST_PARAM_SORT_DIRECTION_DESC, $guiTableDataRequestTransfer->getOrderDirection());
        $this->assertEquals(static::TEST_PARAM_PAGE, $guiTableDataRequestTransfer->getPage());
        $this->assertEquals(static::TEST_PARAM_PAGE_SIZE, $guiTableDataRequestTransfer->getPageSize());
        $this->assertCount(1, $guiTableDataRequestTransfer->getFilters());
        $this->assertEquals(static::TEST_PARAM_FILTER, $guiTableDataRequestTransfer->getFilters());
        $this->assertEquals(static::TEST_ID_LOCALE, $guiTableDataRequestTransfer->getIdLocale());
    }

    /**
     * @return void
     */
    public function testBuildGuiTableDataRequestReturnsGuiTableDataRequestTransferWithDefaultValues(): void
    {
        // Arrange
        $this->setLocaleFacadeMockDependency();
        $this->tester->setDependency(GuiTableDependencyProvider::PLUGINS_REQUEST_FILTER_VALUE_NORMALIZER, []);
        $requestParams = ['sortBy' => static::TEST_PARAM_SORT_BY];
        /** @var \Spryker\Zed\GuiTable\GuiTableConfig $guiTableConfig */
        $guiTableConfig = $this->tester->getModuleConfig();

        // Act
        $guiTableDataRequestTransfer = $this->tester->getFacade()->buildGuiTableDataRequest(
            $requestParams,
            new GuiTableConfigurationTransfer()
        );

        // Assert
        $this->assertIsArray($guiTableDataRequestTransfer->getFilters());
        $this->assertCount(0, $guiTableDataRequestTransfer->getFilters());
        $this->assertEquals(static::TEST_PARAM_SORT_DIRECTION_ASC, $guiTableDataRequestTransfer->getOrderDirection());
        $this->assertEquals(1, $guiTableDataRequestTransfer->getPage());
        $this->assertEquals($guiTableConfig->getDefaultPageSize(), $guiTableDataRequestTransfer->getPageSize());
    }

    /**
     * @return void
     */
    public function testBuildGuiTableDataRequestExecutesRequestFilterValueNormalizerPlugins(): void
    {
        // Arrange
        $this->setLocaleFacadeMockDependency();
        $this->setDateRangeRequestFilterValueNormalizerPluginMockDependency();
        $requestParams = ['filter' => json_encode(static::TEST_PARAM_FILTER)];
        $guiTableFiltersConfigurationTransfer = (new GuiTableFiltersConfigurationTransfer())
            ->addItem(
                (new GuiTableFilterTransfer())
                    ->setId('filterId')
                    ->setType(static::TEST_TYPE_DATE)
            );
        $guiTableConfigurationTransfer = (new GuiTableConfigurationTransfer())
            ->setFilters($guiTableFiltersConfigurationTransfer);

        // Act
        $guiTableDataRequestTransfer = $this->tester->getFacade()->buildGuiTableDataRequest(
            $requestParams,
            $guiTableConfigurationTransfer
        );
        $filterValue = $guiTableDataRequestTransfer->getFilters()['filterId'];

        // Assert
        $this->assertEquals(static::TEST_VALUE_DATE_FORMATTED, $filterValue);
    }

    /**
     * @return void
     */
    public function testFormatGuiTableDataResponseReturnsArrayOfData(): void
    {
        // Arrange
        $this->tester->setDependency(GuiTableDependencyProvider::PLUGINS_RESPONSE_COLUMN_VALUE_FORMATTER, []);
        $guiTableDataResponseTransfer = $this->getGuiTableDataResponseTransfer();

        // Act
        $formattedGuiTableDataResponse = $this->tester->getFacade()->formatGuiTableDataResponse(
            $guiTableDataResponseTransfer,
            new GuiTableConfigurationTransfer()
        );

        // Assert

        $expected = [
            GuiTableDataResponseTransfer::PAGE => static::TEST_PARAM_PAGE,
            GuiTableDataResponseTransfer::TOTAL => static::TEST_PARAM_TOTAL,
            GuiTableDataResponseTransfer::PAGE_SIZE => static::TEST_PARAM_PAGE_SIZE,
            static::KEY_DATA_RESPONSE_ARRAY_DATA => static::TEST_TABLE_DATA,
        ];

        $this->assertIsArray($formattedGuiTableDataResponse);
        $this->assertEquals($expected, $formattedGuiTableDataResponse);
    }

    /**
     * @return void
     */
    public function testFormatGuiTableDataResponseExecutesResponseColumnValueFormatterPlugins(): void
    {
        // Arrange
        $this->setDateResponseColumnValueFormatterPluginMockDependency();
        $guiTableDataResponseTransfer = $this->getGuiTableDataResponseTransfer();
        $guiTableConfigurationTransfer = (new GuiTableConfigurationTransfer())
            ->addColumn((new GuiTableColumnConfigurationTransfer())->setId(self::TEST_COLUMN_ID_1)->setType(static::TEST_TYPE_DATE))
            ->addColumn((new GuiTableColumnConfigurationTransfer())->setId(self::TEST_COLUMN_ID_2)->setType('column_type1'));

        // Act
        $formattedGuiTableDataResponse = $this->tester->getFacade()->formatGuiTableDataResponse(
            $guiTableDataResponseTransfer,
            $guiTableConfigurationTransfer
        );

        // Assert
        $this->assertIsArray($formattedGuiTableDataResponse);
        $this->assertEquals(static::TEST_VALUE_DATE_FORMATTED, $formattedGuiTableDataResponse[static::KEY_DATA_RESPONSE_ARRAY_DATA][0][self::TEST_COLUMN_ID_1]);
        $this->assertEquals(static::TEST_VALUE_DATE_FORMATTED, $formattedGuiTableDataResponse[static::KEY_DATA_RESPONSE_ARRAY_DATA][1][self::TEST_COLUMN_ID_1]);
    }

    /**
     * @return void
     */
    protected function setLocaleFacadeMockDependency(): void
    {
        $localeFacadeMock = $this->getMockBuilder(GuiTableToLocaleFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $localeFacadeMock->method('getCurrentLocale')->willReturn(
            (new LocaleTransfer())->setIdLocale(static::TEST_ID_LOCALE)
        );

        $this->tester->setDependency(GuiTableDependencyProvider::FACADE_LOCALE, $localeFacadeMock);
    }

    /**
     * @return void
     */
    protected function setDateRangeRequestFilterValueNormalizerPluginMockDependency(): void
    {
        $dateRangeRequestFilterValueNormalizerPluginMock = $this->getMockBuilder(DateRangeRequestFilterValueNormalizerPlugin::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dateRangeRequestFilterValueNormalizerPluginMock->method('getFilterType')->willReturn(static::TEST_TYPE_DATE);
        $dateRangeRequestFilterValueNormalizerPluginMock->method('normalizeFilterValue')->willReturn(static::TEST_VALUE_DATE_FORMATTED);

        $this->tester->setDependency(
            GuiTableDependencyProvider::PLUGINS_REQUEST_FILTER_VALUE_NORMALIZER,
            [$dateRangeRequestFilterValueNormalizerPluginMock]
        );
    }

    /**
     * @return void
     */
    protected function setDateResponseColumnValueFormatterPluginMockDependency(): void
    {
        $dateResponseColumnValueFormatterPluginMock = $this->getMockBuilder(DateResponseColumnValueFormatterPlugin::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dateResponseColumnValueFormatterPluginMock->method('getColumnType')->willReturn(static::TEST_TYPE_DATE);
        $dateResponseColumnValueFormatterPluginMock->method('formatColumnValue')->willReturn(static::TEST_VALUE_DATE_FORMATTED);

        $this->tester->setDependency(
            GuiTableDependencyProvider::PLUGINS_RESPONSE_COLUMN_VALUE_FORMATTER,
            [$dateResponseColumnValueFormatterPluginMock]
        );
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function getGuiTableDataResponseTransfer(): GuiTableDataResponseTransfer
    {
        $rows = array_map(
            function ($data) {
                return (new GuiTableRowDataResponseTransfer())->setResponseData($data);
            },
            static::TEST_TABLE_DATA
        );

        return (new GuiTableDataResponseTransfer())
            ->setRows(new ArrayObject($rows))
            ->setPage(static::TEST_PARAM_PAGE)
            ->setPageSize(static::TEST_PARAM_PAGE_SIZE)
            ->setTotal(static::TEST_PARAM_TOTAL);
    }
}
