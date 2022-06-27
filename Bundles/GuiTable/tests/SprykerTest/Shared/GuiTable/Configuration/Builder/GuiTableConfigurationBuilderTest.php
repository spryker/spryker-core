<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GuiTable\Configuration\Builder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\Exception\InvalidConfigurationException;
use TypeError;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GuiTable
 * @group Configuration
 * @group Builder
 * @group GuiTableConfigurationBuilderTest
 * Add your own group annotations below this line
 */
class GuiTableConfigurationBuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Shared\GuiTable\GuiTableSharedTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const ID_ALT_COLUMN_SOURCE = 'name';

    /**
     * @var string
     */
    protected const ID_IMAGE_COLUMN = 'image';

    /**
     * @var string
     */
    protected const TITLE_IMAGE_COLUMN = 'image';

    /**
     * @uses \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface::COLUMN_TYPE_IMAGE
     *
     * @var string
     */
    protected const COLUMN_TYPE_IMAGE = 'image';

    /**
     * @return void
     */
    public function testAddColumnImageAddsImageColumn(): void
    {
        // Arrange
        $guiTableConfigurationBuilder = $this->tester->createGuiTableConfigurationBuilder();

        // Act
        $guiTableConfigurationBuilder->addColumnImage(static::ID_IMAGE_COLUMN, static::TITLE_IMAGE_COLUMN, false, false);

        // Assert
        $this->assertArrayHasKey(static::TITLE_IMAGE_COLUMN, $guiTableConfigurationBuilder->getColumns());
    }

    /**
     * @return void
     */
    public function testAddColumnImageCreatesInstanceOfTableColumnConfigurationTransfer(): void
    {
        // Arrange
        $guiTableConfigurationBuilder = $this->tester->createGuiTableConfigurationBuilder();

        // Act
        $guiTableConfigurationBuilder->addColumnImage(static::ID_IMAGE_COLUMN, static::TITLE_IMAGE_COLUMN, false, false);
        $guiTableColumnConfigurationTransfer = $guiTableConfigurationBuilder->getColumns()[static::ID_IMAGE_COLUMN];

        // Assert
        $this->assertTrue($guiTableColumnConfigurationTransfer instanceof GuiTableColumnConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testAddColumnImageAddsImageWithCorrectAttributesWithoutAltAttribute()
    {
        // Arrange
        $guiTableConfigurationBuilder = $this->tester->createGuiTableConfigurationBuilder();

        // Act
        $guiTableConfigurationBuilder->addColumnImage(static::ID_IMAGE_COLUMN, static::TITLE_IMAGE_COLUMN, false, false);
        $guiTableColumnConfigurationTransfer = $this->extractAddedColumn(static::ID_IMAGE_COLUMN, $guiTableConfigurationBuilder);

        // Assert
        $this->assertColumnAttributesMatchExpected($guiTableColumnConfigurationTransfer, static::TITLE_IMAGE_COLUMN, false, false, static::COLUMN_TYPE_IMAGE, []);
    }

     /**
      * @return void
      */
    public function testAddColumnImageAddsCorrectAttributesWithAltSource(): void
    {
        // Arrange
        $expectedTypeOptions = ['alt' => sprintf('${row.%s}', static::ID_ALT_COLUMN_SOURCE)];
        $guiTableConfigurationBuilder = $this->tester->createGuiTableConfigurationBuilder();

        // Act
        $guiTableConfigurationBuilder->addColumnImage(static::ID_IMAGE_COLUMN, static::TITLE_IMAGE_COLUMN, false, false, static::ID_ALT_COLUMN_SOURCE);
        $guiTableColumnConfigurationTransfer = $this->extractAddedColumn(static::ID_IMAGE_COLUMN, $guiTableConfigurationBuilder);

        // Assert
        $this->assertColumnAttributesMatchExpected($guiTableColumnConfigurationTransfer, static::TITLE_IMAGE_COLUMN, false, false, static::COLUMN_TYPE_IMAGE, $expectedTypeOptions);
    }

    /**
     * @return void
     */
    public function testAddColumnImageWithWrongTypeOfAltSourceThrowsException(): void
    {
        // Arrange
        $guiTableConfigurationBuilder = $this->tester->createGuiTableConfigurationBuilder();

        // Assert
        $this->expectException(TypeError::class);

        // Act
        $guiTableConfigurationBuilder->addColumnImage(static::ID_IMAGE_COLUMN, static::TITLE_IMAGE_COLUMN, false, false, []);
    }

    /**
     * @return void
     */
    public function testAddColumnImageWithDuplicatingColumnNameThrowsException(): void
    {
        // Arrange
        $guiTableConfigurationBuilder = $this->tester->createGuiTableConfigurationBuilder();

        // Assert
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage(sprintf('Column with id "%s" already exists', static::ID_IMAGE_COLUMN));

        // Act
        $guiTableConfigurationBuilder->addColumnImage(static::ID_IMAGE_COLUMN, static::TITLE_IMAGE_COLUMN, false, false, null);
        $guiTableConfigurationBuilder->addColumnImage(static::ID_IMAGE_COLUMN, static::TITLE_IMAGE_COLUMN, false, false, null);
    }

    /**
     * @param string $idColumn
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    protected function extractAddedColumn(
        string $idColumn,
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ): GuiTableColumnConfigurationTransfer {
        return $guiTableConfigurationBuilder->getColumns()[$idColumn];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param string $columnType
     * @param array<string, string>|null $expectedTypeOptions
     *
     * @return void
     */
    protected function assertColumnAttributesMatchExpected(
        GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer,
        string $title,
        bool $isSortable,
        bool $isHideable,
        string $columnType,
        ?array $expectedTypeOptions = null
    ): void {
        $this->assertEquals($title, $guiTableColumnConfigurationTransfer->getTitle());
        $this->assertEquals($isSortable, $guiTableColumnConfigurationTransfer->getSortable());
        $this->assertEquals($isHideable, $guiTableColumnConfigurationTransfer->getHideable());
        $this->assertEquals($expectedTypeOptions, $guiTableColumnConfigurationTransfer->getTypeOptions());
        $this->assertEquals($columnType, $guiTableColumnConfigurationTransfer->getType());
    }
}
