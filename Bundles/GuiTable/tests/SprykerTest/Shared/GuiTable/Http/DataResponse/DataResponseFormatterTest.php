<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\GuiTable\Http\DataResponse;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface;
use Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatter;
use SprykerTest\Shared\GuiTable\GuiTableSharedTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group GuiTable
 * @group Http
 * @group DataResponse
 * @group DataResponseFormatterTest
 * Add your own group annotations below this line
 */
class DataResponseFormatterTest extends Unit
{
    /**
     * @uses \Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatter::KEY_DATA_RESPONSE_ARRAY_DATA
     *
     * @var string
     */
    protected const KEY_DATA_RESPONSE_ARRAY_DATA = 'data';

    /**
     * @var string
     */
    protected const TIMEZONE_UTC = 'UTC';

    /**
     * @var int
     */
    protected const TEST_PARAM_PAGE = 3;

    /**
     * @var int
     */
    protected const TEST_PARAM_PAGE_SIZE = 5;

    /**
     * @var int
     */
    protected const TEST_PARAM_TOTAL = 100;

    /**
     * @var string
     */
    protected const TEST_COLUMN_ID_1 = 'columnId1';

    /**
     * @var string
     */
    protected const TEST_COLUMN_ID_2 = 'columnId2';

    /**
     * @var string
     */
    protected const TEST_VALUE_DATE = '01.01.2023';

    /**
     * @var string
     */
    protected const TEST_VALUE_DATE_FORMATTED_DEFAULT = '2023-01-01T01:00:00+01:00';

    /**
     * @var string
     */
    protected const TEST_VALUE_DATE_FORMATTED_UTC = '2023-01-01T00:00:00+00:00';

    /**
     * @var string
     */
    protected const TEST_TYPE_DATE = 'date';

    /**
     * @var array<array<string, string>>
     */
    protected const TEST_TABLE_DATA = [
        [self::TEST_COLUMN_ID_1 => self::TEST_VALUE_DATE, self::TEST_COLUMN_ID_2 => 'value1'],
        [self::TEST_COLUMN_ID_1 => self::TEST_VALUE_DATE, self::TEST_COLUMN_ID_2 => 'value2'],
    ];

    /**
     * @var \SprykerTest\Shared\GuiTable\GuiTableSharedTester
     */
    protected GuiTableSharedTester $tester;

    /**
     * @return void
     */
    public function testFormatGuiTableDataResponseReturnsArrayOfData(): void
    {
        // Arrange
        $guiTableDataResponseTransfer = $this->createGuiTableDataResponseTransfer();
        $expectedResult = [
            GuiTableDataResponseTransfer::PAGE => static::TEST_PARAM_PAGE,
            GuiTableDataResponseTransfer::TOTAL => static::TEST_PARAM_TOTAL,
            GuiTableDataResponseTransfer::PAGE_SIZE => static::TEST_PARAM_PAGE_SIZE,
            static::KEY_DATA_RESPONSE_ARRAY_DATA => static::TEST_TABLE_DATA,
        ];

        $dataResponseFormatter = new DataResponseFormatter(
            $this->tester->createGuiTableToUtilDateTimeServiceBridge(),
            $this->createGuiTableConfigMock(),
        );

        // Act
        $formattedGuiTableDataResponse = $dataResponseFormatter->formatGuiTableDataResponse(
            $guiTableDataResponseTransfer,
            new GuiTableConfigurationTransfer(),
        );

        // Assert
        $this->assertEquals($expectedResult, $formattedGuiTableDataResponse);
    }

    /**
     * @return void
     */
    public function testFormatGuiTableDataResponseFormatsDateTimeWhenTimezoneConfigNotSpecified(): void
    {
        // Arrange
        $guiTableDataResponseTransfer = $this->createGuiTableDataResponseTransfer();
        $guiTableConfigurationTransfer = (new GuiTableConfigurationTransfer())
            ->addColumn((new GuiTableColumnConfigurationTransfer())->setId(static::TEST_COLUMN_ID_1)->setType(static::TEST_TYPE_DATE))
            ->addColumn((new GuiTableColumnConfigurationTransfer())->setId(static::TEST_COLUMN_ID_2)->setType('column_type1'));

        $dataResponseFormatter = new DataResponseFormatter(
            $this->tester->createGuiTableToUtilDateTimeServiceBridge(),
            $this->createGuiTableConfigMock(),
        );

        // Act
        $formattedGuiTableDataResponse = $dataResponseFormatter->formatGuiTableDataResponse(
            $guiTableDataResponseTransfer,
            $guiTableConfigurationTransfer,
        );

        // Assert
        $this->assertSame(static::TEST_VALUE_DATE_FORMATTED_DEFAULT, $formattedGuiTableDataResponse[static::KEY_DATA_RESPONSE_ARRAY_DATA][0][static::TEST_COLUMN_ID_1]);
        $this->assertSame(static::TEST_VALUE_DATE_FORMATTED_DEFAULT, $formattedGuiTableDataResponse[static::KEY_DATA_RESPONSE_ARRAY_DATA][1][static::TEST_COLUMN_ID_1]);
    }

    /**
     * @return void
     */
    public function testFormatGuiTableDataResponseFormatsDateTimeWhenTimezoneConfigIsProvided(): void
    {
        // Arrange
        $guiTableDataResponseTransfer = $this->createGuiTableDataResponseTransfer();
        $guiTableConfigurationTransfer = (new GuiTableConfigurationTransfer())
            ->addColumn((new GuiTableColumnConfigurationTransfer())->setId(static::TEST_COLUMN_ID_1)->setType(static::TEST_TYPE_DATE))
            ->addColumn((new GuiTableColumnConfigurationTransfer())->setId(static::TEST_COLUMN_ID_2)->setType('column_type1'));

        $dataResponseFormatter = new DataResponseFormatter(
            $this->tester->createGuiTableToUtilDateTimeServiceBridge(),
            $this->createGuiTableConfigMock(static::TIMEZONE_UTC),
        );

        // Act
        $formattedGuiTableDataResponse = $dataResponseFormatter->formatGuiTableDataResponse(
            $guiTableDataResponseTransfer,
            $guiTableConfigurationTransfer,
        );

        // Assert
        $this->assertSame(static::TEST_VALUE_DATE_FORMATTED_UTC, $formattedGuiTableDataResponse[static::KEY_DATA_RESPONSE_ARRAY_DATA][0][static::TEST_COLUMN_ID_1]);
        $this->assertSame(static::TEST_VALUE_DATE_FORMATTED_UTC, $formattedGuiTableDataResponse[static::KEY_DATA_RESPONSE_ARRAY_DATA][1][static::TEST_COLUMN_ID_1]);
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function createGuiTableDataResponseTransfer(): GuiTableDataResponseTransfer
    {
        $rows = array_map(
            function ($data) {
                return (new GuiTableRowDataResponseTransfer())->setResponseData($data);
            },
            static::TEST_TABLE_DATA,
        );

        return (new GuiTableDataResponseTransfer())
            ->setRows(new ArrayObject($rows))
            ->setPage(static::TEST_PARAM_PAGE)
            ->setPageSize(static::TEST_PARAM_PAGE_SIZE)
            ->setTotal(static::TEST_PARAM_TOTAL);
    }

    /**
     * @param string|null $timezone
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface
     */
    protected function createGuiTableConfigMock(?string $timezone = null): GuiTableConfigInterface
    {
        $guiTableConfigMock = $this->getMockBuilder(GuiTableConfigInterface::class)->getMock();
        $guiTableConfigMock->method('getDefaultTimezone')->willReturn($timezone);

        return $guiTableConfigMock;
    }
}
