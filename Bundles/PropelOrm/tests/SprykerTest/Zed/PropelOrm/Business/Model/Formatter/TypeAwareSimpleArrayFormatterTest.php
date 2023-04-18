<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Model\Formatter;

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\TypeAwareSimpleArrayFormatter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PropelOrm
 * @group Business
 * @group Model
 * @group Formatter
 * @group TypeAwareSimpleArrayFormatterTest
 * Add your own group annotations below this line
 */
class TypeAwareSimpleArrayFormatterTest extends Unit
{
    /**
     * @var array<string, string>
     */
    protected $asColumns = [
        'table.columnName1' => 'columnName1',
        'table.columnName2' => 'columnName2',
    ];

    /**
     * @return void
     */
    public function testGetStructuredArrayFromRowItReturnsSimpleArrayRowIfSimpleArrayRowIsFalse(): void
    {
        //Arrange
        $formatter = Stub::make(
            TypeAwareSimpleArrayFormatter::class,
            [
                'getSimpleArrayFormatterRow' => false,
                'canFindColumnInTableMap' => true,
                'isBooleanColumnType' => true,
            ],
        );

        // Act
        $result = $formatter->getStructuredArrayFromRow([false, 'DEFAULT']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testGetStructuredArrayFromRowItReturnsSimpleArrayRowIfSimpleArrayRowIsAValue(): void
    {
        // Arrange
        $formatter = Stub::make(
            TypeAwareSimpleArrayFormatter::class,
            [
                'getSimpleArrayFormatterRow' => 'myvalue',
                'canFindColumnInTableMap' => true,
                'isBooleanColumnType' => true,
            ],
        );

        // Act
        $result = $formatter->getStructuredArrayFromRow(['myvalue', 'DEFAULT']);

        // Assert
        $this->assertSame('myvalue', $result);
    }

    /**
     * @return void
     */
    public function testGetStructuredArrayFromRowItCastValuesToBooleanIfColumnTypeIsBoolean(): void
    {
        // Arrange
        $data = [
            'table.columnName1' => 1,
            'table.columnName2' => 0,
        ];

        $formatter = Stub::make(
            TypeAwareSimpleArrayFormatter::class,
            [
                'getSimpleArrayFormatterRow' => $data,
                'getTableColumnArray' => $data,
                'getNormalizedAsColumnsArray' => $data,
                'canFindColumnInTableMap' => true,
                'isBooleanColumnType' => true,
            ],
        );

        $formatter->setAsColumns($this->asColumns);

        // Act
        $result = $formatter->getStructuredArrayFromRow([1, 0]);

        // Assert
        $this->assertSame(
            [
                'table.columnName1' => true,
                'table.columnName2' => false,
            ],
            $result,
        );
    }

    /**
     * @return void
     */
    public function testGetStructuredArrayFromRowItDoesNotCastValuesToBooleanIfColumnTypeIsNotBoolean(): void
    {
        // Arrange
        $data = [
            'table.columnName1' => 1,
            'table.columnName2' => 0,
        ];

        $formatter = Stub::make(
            TypeAwareSimpleArrayFormatter::class,
            [
                'getSimpleArrayFormatterRow' => $data,
                'getTableColumnArray' => $data,
                'getNormalizedAsColumnsArray' => $data,
                'canFindColumnInTableMap' => true,
                'isBooleanColumnType' => false,
            ],
        );

        $formatter->setAsColumns($this->asColumns);

        // Act
        $result = $formatter->getStructuredArrayFromRow([1, 0]);

        // Assert
        $this->assertSame(
            [
                'table.columnName1' => 1,
                'table.columnName2' => 0,
            ],
            $result,
        );
    }
}
