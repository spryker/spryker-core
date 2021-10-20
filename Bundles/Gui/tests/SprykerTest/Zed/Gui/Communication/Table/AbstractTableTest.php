<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Gui\Communication\Table;

use Codeception\Test\Unit;
use Spryker\Zed\Gui\Communication\Exception\TableException;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerTest\Zed\Gui\Communication\Fixture\DownloadTable;
use SprykerTest\Zed\Gui\Communication\Fixture\DownloadTableWithOrderedHeadersAndFormatting;
use SprykerTest\Zed\Gui\Communication\Fixture\DownloadTableWithoutGetDownloadQueryMethod;
use SprykerTest\Zed\Gui\Communication\Fixture\FooTable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Gui
 * @group Communication
 * @group Table
 * @group AbstractTableTest
 * Add your own group annotations below this line
 */
class AbstractTableTest extends Unit
{
    /**
     * @var string
     */
    public const COL_ONE = 'one';

    /**
     * @var string
     */
    public const COL_TWO = 'two';

    /**
     * @var \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    protected $table;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->table = new FooTable();

        $request = new Request();
        $this->table->setRequest($request);
    }

    /**
     * @return void
     */
    public function testGetOrdersDefault(): void
    {
        $config = new TableConfiguration();
        $config->setHeader([
            static::COL_ONE => 'One',
            static::COL_TWO => 'Two',
        ]);
        $config->setSortable([
            static::COL_ONE,
            static::COL_TWO,
        ]);

        $result = $this->table->getOrders($config);
        $expected = [
            [
                'column' => 0,
                'dir' => 'asc',
            ],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @return void
     */
    public function testGetOrdersWithCustomSortField(): void
    {
        $config = new TableConfiguration();
        $config->setHeader([
            static::COL_ONE => 'One',
            static::COL_TWO => 'Two',
        ]);
        $config->setSortable([
            static::COL_ONE,
            static::COL_TWO,
        ]);

        $config->setDefaultSortField(static::COL_TWO);

        $result = $this->table->getOrders($config);
        $expected = [
            [
              'column' => 1,
              'dir' => 'asc',
            ],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @return void
     */
    public function testGetOrdersWithCustomSortFieldAndCustomDirection(): void
    {
        $config = new TableConfiguration();
        $config->setHeader([
            static::COL_ONE => 'One',
            static::COL_TWO => 'Two',
        ]);
        $config->setSortable([
            static::COL_ONE,
            static::COL_TWO,
        ]);

        $config->setDefaultSortField(static::COL_TWO, TableConfiguration::SORT_DESC);

        $result = $this->table->getOrders($config);
        $expected = [
            [
                'column' => 1,
                'dir' => 'desc',
            ],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @return void
     */
    public function testGetOrdersWithDeprecatedIndexAndDirection(): void
    {
        $config = new TableConfiguration();
        $config->setHeader([
            static::COL_ONE => 'One',
            static::COL_TWO => 'Two',
        ]);
        $config->setSortable([
            static::COL_ONE,
            static::COL_TWO,
        ]);

        $config->setDefaultSortColumnIndex(1);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $result = $this->table->getOrders($config);
        $expected = [
            [
                'column' => 1,
                'dir' => 'desc',
            ],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @return void
     */
    public function testGetCSVHeadersThrowsExceptionWhenMethodNotImplemented(): void
    {
        // Assert
        $this->expectException(TableException::class);
        $this->expectExceptionMessage(sprintf(
            'You need to implement `%s::getCsvHeaders()` in your `%s`',
            AbstractTable::class,
            FooTable::class,
        ));

        // Act
        $this->table->streamDownload();
    }

    /**
     * @return void
     */
    public function testGetDownloadQueryThrowsExceptionWhenNotImplemented(): void
    {
        // Arrange
        $table = new DownloadTableWithoutGetDownloadQueryMethod();

        // Assert
        $this->expectException(TableException::class);
        $this->expectExceptionMessage(sprintf(
            'You need to implement `%s::getDownloadQuery()` in your `%s`',
            AbstractTable::class,
            DownloadTableWithoutGetDownloadQueryMethod::class,
        ));

        // Act
        $table->streamDownload()->send();
    }

    /**
     * @return void
     */
    public function testStreamDownloadReturnsStreamedResponseWithCSV(): void
    {
        // Arrange
        $table = new DownloadTable();

        // Act
        $streamedResponse = $table->streamDownload();
        ob_start();
        $streamedResponse->send();
        $streamedResponseOutput = ob_get_contents();
        ob_end_clean();

        // Assert
        $this->assertInstanceOf(StreamedResponse::class, $streamedResponse);

        $expectedCsvStreamData = implode(PHP_EOL, [
            '"Header column 1","Header column 2"',
            '"Row 1 column 1","Row 1 column 2"',
            '"Row 2 column 1","Row 2 column 2"',
        ]) . PHP_EOL;

        $this->assertSame($expectedCsvStreamData, $streamedResponseOutput);
    }

    /**
     * @return void
     */
    public function testStreamDownloadReturnsStreamedResponseWithOrderedAndFormattedCSV(): void
    {
        // Arrange
        $table = new DownloadTableWithOrderedHeadersAndFormatting();

        // Act
        $streamedResponse = $table->streamDownload();
        ob_start();
        $streamedResponse->send();
        $streamedResponseOutput = ob_get_contents();
        ob_end_clean();

        // Assert
        $this->assertInstanceOf(StreamedResponse::class, $streamedResponse);

        $expectedCsvStreamData = implode(PHP_EOL, [
            '"Header column 1","Header column 2"',
            '"Row 1 column 2","Formatted Row 1 column 1"',
            '"Row 2 column 2","Formatted Row 2 column 1"',
        ]) . PHP_EOL;

        $this->assertSame($expectedCsvStreamData, $streamedResponseOutput);
    }
}
