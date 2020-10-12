<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Builder\ObjectBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ColumnsCollectionTransfer;
use Generated\Shared\Transfer\ColumnTransfer;
use Generated\Shared\Transfer\FilesToGenerateCollectionTransfer;
use Generated\Shared\Transfer\FileToGenerateTransfer;
use Propel\Generator\Builder\Om\TableMapBuilder;
use Propel\Generator\Model\PropelTypes;
use Propel\Runtime\Propel;
use Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PropelOrm
 * @group Business
 * @group Builder
 * @group ObjectBuilder
 * @group ObjectBuilderTest
 * Add your own group annotations below this line
 */
class ObjectBuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PropelOrm\PropelOrmBusinessTester
     */
    protected $tester;

    /**
     * @var \Propel\Generator\Model\Table
     */
    protected $table;

    protected const TABLE_NAME = 'Foo';
    protected const COLUMN_NAME = 'testColumn';
    protected const COLUMN_TYPE = PropelTypes::INTEGER;

    /**
     * @return array
     */
    protected function getFilesToGenerate(): FilesToGenerateCollectionTransfer
    {
        $fooMapFileToGenerateTransfer = (new FileToGenerateTransfer())
            ->setFileName(__DIR__ . '/Map/FooTableMap.php')
            ->setBuilderClass(TableMapBuilder::class);

        $fooFileToGenerateTransfer = (new FileToGenerateTransfer())
            ->setFileName(__DIR__ . '/Base/Foo.php')
            ->setBuilderClass(ObjectBuilder::class);

        return (new FilesToGenerateCollectionTransfer())
            ->addFilesToGenerate($fooMapFileToGenerateTransfer)
            ->addFilesToGenerate($fooFileToGenerateTransfer);
    }

    /**
     * @return void
     */
    protected function _before(): void
    {
        $columnTransfer = (new ColumnTransfer())->fromArray([
            'name' => static::COLUMN_NAME,
            'type' => static::COlUMN_TYPE,
        ]);

        $columnsCollectionTransfer = (new ColumnsCollectionTransfer())->addColumns($columnTransfer);
        $this->table = $this->tester->createTable(static::TABLE_NAME, $columnsCollectionTransfer);

        $this->tester->writePropelFiles($this->getFilesToGenerate(), $this->table);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        $this->tester->deletePropelFiles($this->getFilesToGenerate());
        $this->tester->dropTable($this->table);
    }

    /**
     * @return void
     */
    public function testSaveShouldNotThrowAnException(): void
    {
        // Arrange
        $connection = Propel::getConnection();
        $foo = new Foo();
        $foo->setTestcolumn(1);

        // Act
        $foo->save($connection);
    }
}
