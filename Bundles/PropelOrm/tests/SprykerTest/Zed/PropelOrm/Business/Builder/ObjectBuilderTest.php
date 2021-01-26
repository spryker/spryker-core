<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Builder;

use Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilder;
use SprykerTest\Zed\PropelOrm\Business\Builder\Fixtures\Foo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PropelOrm
 * @group Business
 * @group Builder
 * @group ObjectBuilderTest
 * Add your own group annotations below this line
 */
class ObjectBuilderTest extends AbstractBuilderTester
{
    protected const FOO_BUILDER_CLASS = ObjectBuilder::class;

    /**
     * @return void
     */
    public function testSaveShouldNotThrowAnException(): void
    {
        // Arrange
        $foo = new Foo();
        $foo->setIdFoo(1);
        $expectedCount = 1;

        // Act
        $affectedCount = $foo->save();

        // Assert
        $this->assertSame($expectedCount, $affectedCount);
    }

    /**
     * @return void
     */
    public function testBooleanColumnWithDefaultValueShouldBeNotModified(): void
    {
        // Arrange
        $foo = new Foo();

        // Assert
        $this->assertSame(self::TESTING_COLUMN_FLAG_DEFAULT_VALUE, $foo->getFlagDefaultFoo());
        $this->assertFalse($foo->isColumnModified(self::TESTING_TABLE_NAME . '.' . self::TESTING_COLUMN_FLAG_DEFAULT_VALUE_NAME));
    }

    /**
     * @return void
     */
    public function testBooleanColumnWithDefaultValueShouldBeModified(): void
    {
        // Arrange
        $foo = new Foo();

        // Act
        $foo->setFlagDefaultFoo(self::TESTING_COLUMN_FLAG_DEFAULT_VALUE);

        // Assert
        $this->assertTrue($foo->isColumnModified(self::TESTING_TABLE_NAME . '.' . self::TESTING_COLUMN_FLAG_DEFAULT_VALUE_NAME));
    }

    /**
     * @return void
     */
    public function testVarcharColumnWithDefaultValueShouldBeNotModified(): void
    {
        // Arrange
        $foo = new Foo();

        // Assert
        $this->assertSame(self::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE, $foo->getStringDefaultFoo());
        $this->assertFalse($foo->isColumnModified(self::TESTING_TABLE_NAME . '.' . self::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE_NAME));
    }

    /**
     * @return void
     */
    public function testVarcharColumnWithDefaultValueShouldBeModified(): void
    {
        // Arrange
        $foo = new Foo();

        // Act
        $foo->setStringDefaultFoo(self::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE);

        // Assert
        $this->assertTrue($foo->isColumnModified(self::TESTING_TABLE_NAME . '.' . self::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE_NAME));
    }

    /**
     * @return void
     */
    public function testBooleanColumnWithoutDefaultValueShouldBeNotModified(): void
    {
        // Arrange
        $foo = new Foo();

        // Assert
        $this->assertFalse($foo->isColumnModified(self::TESTING_TABLE_NAME . '.' . self::TESTING_COLUMN_FLAG_NAME));
    }

    /**
     * @return void
     */
    public function testBooleanColumnWithoutDefaultValueShouldBeModified(): void
    {
        // Arrange
        $foo = new Foo();

        // Act
        $foo->setFlagFoo(true);

        // Assert
        $this->assertTrue($foo->isColumnModified(self::TESTING_TABLE_NAME . '.' . self::TESTING_COLUMN_FLAG_NAME));
    }

    /**
     * @return void
     */
    public function testVarcharColumnWithoutDefaultValueShouldBeNotModified(): void
    {
        // Arrange
        $foo = new Foo();

        // Assert
        $this->assertFalse($foo->isColumnModified(self::TESTING_TABLE_NAME . '.' . self::TESTING_COLUMN_VARCHAR_NAME));
    }

    /**
     * @return void
     */
    public function testVarcharColumnWithoutDefaultValueShouldBeModified(): void
    {
        // Arrange
        $foo = new Foo();

        // Act
        $foo->setStringFoo('The new value from testing method.');

        // Assert
        $this->assertTrue($foo->isColumnModified(self::TESTING_TABLE_NAME . '.' . self::TESTING_COLUMN_VARCHAR_NAME));
    }
}
