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
     * @var bool
     */
    protected const BOOLEAN_VALUE = true;

    /**
     * @var string
     */
    protected const VARCHAR_VALUE = 'The new value from testing method.';

    /**
     * @var string
     */
    protected const VARCHAR_VALUE_2 = 'The additional new value from testing method.';

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
    public function testBooleanColumnWithDefaultValueShouldBeModifiedWhenValueIsSet(): void
    {
        // Arrange
        $columnKeyName = static::TESTING_TABLE_NAME . '.' . static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE_NAME;
        $foo = new Foo();
        $fooExpectChange = new Foo();

        // Act
        $fooExpectChange->setFlagDefaultFoo(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE);

        // Assert
        $this->assertSame(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE, $foo->getFlagDefaultFoo());
        $this->assertFalse($foo->isColumnModified($columnKeyName));
        $this->assertSame(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE, $fooExpectChange->getFlagDefaultFoo());
        $this->assertTrue($fooExpectChange->isColumnModified($columnKeyName));
    }

    /**
     * @return void
     */
    public function testBooleanColumnWithoutDefaultValueShouldBeModifiedWhenValueIsSet(): void
    {
        // Arrange
        $columnKeyName = static::TESTING_TABLE_NAME . '.' . static::TESTING_COLUMN_BOOLEAN_NAME;
        $foo = new Foo();
        $fooExpectChange = new Foo();

        // Act
        $fooExpectChange->setFlagFoo(static::BOOLEAN_VALUE);

        // Assert
        $this->assertNull($foo->getFlagFoo());
        $this->assertFalse($foo->isColumnModified($columnKeyName));
        $this->assertSame(static::BOOLEAN_VALUE, $fooExpectChange->getFlagFoo());
        $this->assertTrue($fooExpectChange->isColumnModified($columnKeyName));
    }

    /**
     * @return void
     */
    public function testBooleanColumnWithDefaultValueOnSavedEntityShouldBeModifiedWhenValueIsSet(): void
    {
        // Arrange
        $columnKeyName = static::TESTING_TABLE_NAME . '.' . static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE_NAME;
        $foo = (new Foo())->setIdFoo(1);
        $fooWithSameValue = (new Foo())->setFlagDefaultFoo(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE);
        $fooExpectChange = (new Foo())->setFlagDefaultFoo(!static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE);

        // Act
        $foo->save();
        $fooWithSameValue->save();
        $fooExpectChange->save();
        $fooWithSameValue->setFlagDefaultFoo(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE);
        $fooExpectChange->setFlagDefaultFoo(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE);

        // Assert
        $this->assertSame(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE, $foo->getFlagDefaultFoo());
        $this->assertFalse($foo->isColumnModified($columnKeyName));
        $this->assertSame(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE, $fooWithSameValue->getFlagDefaultFoo());
        $this->assertFalse($fooWithSameValue->isColumnModified($columnKeyName));
        $this->assertSame(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE, $fooExpectChange->getFlagDefaultFoo());
        $this->assertTrue($fooExpectChange->isColumnModified($columnKeyName));
    }

    /**
     * @return void
     */
    public function testBooleanColumnWithoutDefaultValueOnSavedEntityShouldBeModifiedWhenValueIsSet(): void
    {
        // Arrange
        $columnKeyName = static::TESTING_TABLE_NAME . '.' . static::TESTING_COLUMN_BOOLEAN_NAME;
        $foo = (new Foo())->setIdFoo(1);
        $fooWithSameValue = (new Foo())->setFlagFoo(static::BOOLEAN_VALUE);
        $fooExpectChange = (new Foo())->setFlagFoo(!static::BOOLEAN_VALUE);

        // Act
        $foo->save();
        $fooWithSameValue->save();
        $fooExpectChange->save();
        $fooWithSameValue->setFlagFoo(static::BOOLEAN_VALUE);
        $fooExpectChange->setFlagFoo(static::BOOLEAN_VALUE);

        // Assert
        $this->assertNull($foo->getFlagFoo());
        $this->assertFalse($foo->isColumnModified($columnKeyName));
        $this->assertSame(static::BOOLEAN_VALUE, $fooWithSameValue->getFlagFoo());
        $this->assertFalse($fooWithSameValue->isColumnModified($columnKeyName));
        $this->assertSame(static::BOOLEAN_VALUE, $fooExpectChange->getFlagFoo());
        $this->assertTrue($fooExpectChange->isColumnModified($columnKeyName));
    }

    /**
     * @return void
     */
    public function testVarcharColumnWithDefaultValueShouldBeModifiedWhenValueIsSet(): void
    {
        // Arrange
        $columnKeyName = static::TESTING_TABLE_NAME . '.' . static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE_NAME;
        $foo = new Foo();
        $fooExpectChange = new Foo();

        // Act
        $fooExpectChange->setStringDefaultFoo(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE);

        // Assert
        $this->assertSame(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE, $foo->getStringDefaultFoo());
        $this->assertFalse($foo->isColumnModified($columnKeyName));
        $this->assertSame(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE, $fooExpectChange->getStringDefaultFoo());
        $this->assertTrue($fooExpectChange->isColumnModified($columnKeyName));
    }

    /**
     * @return void
     */
    public function testVarcharColumnWithoutDefaultValueShouldBeModifiedWhenValueIsSet(): void
    {
        // Arrange
        $columnKeyName = static::TESTING_TABLE_NAME . '.' . static::TESTING_COLUMN_VARCHAR_NAME;
        $foo = new Foo();
        $fooExpectChange = new Foo();

        // Act
        $fooExpectChange->setStringFoo(static::VARCHAR_VALUE);

        // Assert
        $this->assertNull($foo->getStringFoo());
        $this->assertFalse($foo->isColumnModified($columnKeyName));
        $this->assertSame(static::VARCHAR_VALUE, $fooExpectChange->getStringFoo());
        $this->assertTrue($fooExpectChange->isColumnModified($columnKeyName));
    }

    /**
     * @return void
     */
    public function testVarcharColumnWithDefaultValueOnSavedEntityShouldBeModifiedWhenValueIsSet(): void
    {
        // Arrange
        $columnKeyName = static::TESTING_TABLE_NAME . '.' . static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE_NAME;
        $foo = (new Foo())->setIdFoo(1);
        $fooWithSameValue = (new Foo())->setStringDefaultFoo(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE);
        $fooExpectChange = (new Foo())->setStringDefaultFoo(static::VARCHAR_VALUE_2);

        // Act
        $foo->save();
        $fooWithSameValue->save();
        $fooExpectChange->save();
        $fooWithSameValue->setStringDefaultFoo(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE);
        $fooExpectChange->setStringDefaultFoo(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE);

        // Assert
        $this->assertSame(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE, $foo->getStringDefaultFoo());
        $this->assertFalse($foo->isColumnModified($columnKeyName));
        $this->assertSame(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE, $fooWithSameValue->getStringDefaultFoo());
        $this->assertFalse($fooWithSameValue->isColumnModified($columnKeyName));
        $this->assertSame(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE, $fooExpectChange->getStringDefaultFoo());
        $this->assertTrue($fooExpectChange->isColumnModified($columnKeyName));
    }

    /**
     * @return void
     */
    public function testVarcharColumnWithoutDefaultValueOnSavedEntityShouldBeModifiedWhenValueIsSet(): void
    {
        // Arrange
        $columnKeyName = static::TESTING_TABLE_NAME . '.' . static::TESTING_COLUMN_VARCHAR_NAME;
        $foo = (new Foo())->setIdFoo(1);
        $fooWithSameValue = (new Foo())->setStringFoo(static::VARCHAR_VALUE);
        $fooExpectChange = (new Foo())->setStringFoo(static::VARCHAR_VALUE_2);

        // Act
        $foo->save();
        $fooWithSameValue->save();
        $fooExpectChange->save();
        $fooWithSameValue->setStringFoo(static::VARCHAR_VALUE);
        $fooExpectChange->setStringFoo(static::VARCHAR_VALUE);

        // Assert
        $this->assertNull($foo->getStringFoo());
        $this->assertFalse($foo->isColumnModified($columnKeyName));
        $this->assertSame(static::VARCHAR_VALUE, $fooWithSameValue->getStringFoo());
        $this->assertFalse($fooWithSameValue->isColumnModified($columnKeyName));
        $this->assertSame(static::VARCHAR_VALUE, $fooExpectChange->getStringFoo());
        $this->assertTrue($fooExpectChange->isColumnModified($columnKeyName));
    }
}
