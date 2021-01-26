<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Builder;

use Codeception\Test\Unit;
use Propel\Generator\Builder\Om\ObjectBuilder;
use Propel\Generator\Builder\Om\TableMapBuilder;
use Propel\Generator\Model\ColumnDefaultValue;
use Propel\Generator\Model\PropelTypes;

abstract class AbstractBuilderTester extends Unit
{
    /**
     * @var \SprykerTest\Zed\PropelOrm\PropelOrmBusinessTester
     */
    protected $tester;

    protected const TESTING_TABLE_NAME = 'foo';
    protected const TESTING_TABLE_NAMESPACE = __NAMESPACE__ . '\\Fixtures';
    protected const TESTING_COLUMN_NAME = 'id_foo';
    protected const TESTING_COLUMN_TYPE = PropelTypes::INTEGER;
    protected const TESTING_COLUMN_BOOLEAN_NAME = 'flag_foo';
    protected const TESTING_COLUMN_BOOLEAN_TYPE = PropelTypes::BOOLEAN;
    protected const TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE_NAME = 'flag_default_foo';
    protected const TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE_TYPE = PropelTypes::BOOLEAN;
    protected const TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE = true;
    protected const TESTING_COLUMN_VARCHAR_NAME = 'string_foo';
    protected const TESTING_COLUMN_VARCHAR_TYPE = PropelTypes::VARCHAR;
    protected const TESTING_COLUMN_VARCHAR_DEFAULT_VALUE_NAME = 'string_default_foo';
    protected const TESTING_COLUMN_VARCHAR_DEFAULT_VALUE_TYPE = PropelTypes::VARCHAR;
    protected const TESTING_COLUMN_VARCHAR_DEFAULT_VALUE = 'lorem impsum';

    protected const FOO_MAP_BUILDER_CLASS = TableMapBuilder::class;
    protected const FOO_BUILDER_CLASS = ObjectBuilder::class;

    /**
     * @return void
     */
    protected function _before(): void
    {
        /**
         * Each column can use additional Domain setters.
         *
         * @see \Propel\Generator\Model\Domain
         */
        $columns = [[
                'name' => static::TESTING_COLUMN_NAME,
                'type' => static::TESTING_COLUMN_TYPE,
            ], [
                'name' => static::TESTING_COLUMN_BOOLEAN_NAME,
                'type' => static::TESTING_COLUMN_BOOLEAN_TYPE,
            ], [
                'name' => static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE_NAME,
                'type' => static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE_TYPE,
                'setDefaultValue' => new ColumnDefaultValue(static::TESTING_COLUMN_BOOLEAN_DEFAULT_VALUE),
            ], [
                'name' => static::TESTING_COLUMN_VARCHAR_NAME,
                'type' => static::TESTING_COLUMN_VARCHAR_TYPE,
                'setSize' => 64,
            ], [
                'name' => static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE_NAME,
                'type' => static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE_TYPE,
                'setDefaultValue' => new ColumnDefaultValue(static::TESTING_COLUMN_VARCHAR_DEFAULT_VALUE),
                'setSize' => 64,
            ]];

        $table = $this->tester->createTable(
            static::TESTING_TABLE_NAME,
            $columns,
            static::TESTING_TABLE_NAMESPACE
        );

        $this->tester->writePropelFiles($this->getFilesToGenerate(), $table);
    }

    /**
     * @return array
     */
    protected function getFilesToGenerate(): array
    {
        return [
            __DIR__ . '/Fixtures/Map/FooTableMap.php' => static::FOO_MAP_BUILDER_CLASS,
            __DIR__ . '/Fixtures/Base/Foo.php' => static::FOO_BUILDER_CLASS,
        ];
    }
}
