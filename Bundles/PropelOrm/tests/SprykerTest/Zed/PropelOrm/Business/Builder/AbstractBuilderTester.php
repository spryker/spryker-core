<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Builder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ColumnTransfer;
use Generated\Shared\Transfer\FilesToGenerateCollectionTransfer;
use Generated\Shared\Transfer\FileToGenerateTransfer;
use Generated\Shared\Transfer\TableTransfer;
use Propel\Generator\Builder\Om\ObjectBuilder;
use Propel\Generator\Builder\Om\TableMapBuilder;
use Propel\Generator\Model\PropelTypes;

abstract class AbstractBuilderTester extends Unit
{
    /**
     * @var \SprykerTest\Zed\PropelOrm\PropelOrmBusinessTester
     */
    protected $tester;

    /**
     * @var \Propel\Generator\Model\Table
     */
    protected $table;

    protected const TESTING_TABLE_NAME = 'foo';
    protected const TESTING_COLUMN_NAME = 'id_foo';
    protected const TESTING_COLUMN_TYPE = PropelTypes::INTEGER;

    protected const FOO_MAP_BUILDER_CLASS = TableMapBuilder::class;
    protected const FOO_BUILDER_CLASS = ObjectBuilder::class;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->table = $this->tester->createTable($this->buildTableTransfer());
        $this->tester->writePropelFiles($this->buildFilesToGenerateCollectionTransfer(), $this->table);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        $this->tester->deletePropelFiles($this->buildFilesToGenerateCollectionTransfer());
        $this->tester->dropTable($this->table);
    }

    /**
     * @return \Generated\Shared\Transfer\FilesToGenerateCollectionTransfer
     */
    protected function buildFilesToGenerateCollectionTransfer(): FilesToGenerateCollectionTransfer
    {
        $fooMapFileToGenerateTransfer = (new FileToGenerateTransfer())
            ->setFileName(__DIR__ . '/Fixtures/Map/FooTableMap.php')
            ->setBuilderClass(static::FOO_MAP_BUILDER_CLASS);

        $fooFileToGenerateTransfer = (new FileToGenerateTransfer())
            ->setFileName(__DIR__ . '/Fixtures/Base/Foo.php')
            ->setBuilderClass(static::FOO_BUILDER_CLASS);

        return (new FilesToGenerateCollectionTransfer())
            ->addFilesToGenerate($fooFileToGenerateTransfer)
            ->addFilesToGenerate($fooMapFileToGenerateTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\TableTransfer
     */
    protected function buildTableTransfer(): TableTransfer
    {
        $columnTransfer = (new ColumnTransfer())->fromArray([
            'name' => static::TESTING_COLUMN_NAME,
            'type' => static::TESTING_COLUMN_TYPE,
        ]);

        return (new TableTransfer())
            ->setName(static::TESTING_TABLE_NAME)
            ->setNamespace(__NAMESPACE__ . '\\Fixtures')
            ->addColumns($columnTransfer);
    }
}
