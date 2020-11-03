<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Builder\QueryBuilder;

use Codeception\Test\Unit;
use Propel\Generator\Builder\Om\ObjectBuilder;
use Propel\Generator\Builder\Om\TableMapBuilder;
use Propel\Generator\Model\Column;
use Propel\Generator\Model\Database;
use Propel\Generator\Model\PropelTypes;
use Propel\Generator\Model\Table;
use Propel\Generator\Platform\DefaultPlatform;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\PropelOrm\Business\Builder\QueryBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PropelOrm
 * @group Business
 * @group Builder
 * @group QueryBuilder
 * @group QueryBuilderTest
 * Add your own group annotations below this line
 */
class QueryBuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PropelOrm\PropelOrmBusinessTester
     */
    protected $tester;

    /**
     * @return array
     */
    protected function getFilesToGenerate(): array
    {
        return [
            __DIR__ . '/Map/FooTableMap.php' => TableMapBuilder::class,
            __DIR__ . '/Base/FooQuery.php' => QueryBuilder::class,
            __DIR__ . '/Base/Foo.php' => ObjectBuilder::class,
        ];
    }

    /**
     * @return void
     */
    protected function _before(): void
    {
        $table = new Table('Foo');
        $column = new Column('testColumn', PropelTypes::INTEGER);
        $table->addColumn($column);
        $table->setNamespace('SprykerTest\Zed\PropelOrm\Business\Builder\QueryBuilder');
        $table->setDatabase(new Database('TestDB', new DefaultPlatform()));

        $this->tester->writePropelFiles($this->getFilesToGenerate(), $table);
    }

    /**
     * @return void
     */
    public function testGeneratedFilterFunctionDoesNotThrowExceptionOnNotIn(): void
    {
        $testQuery = new FooQuery();
        $testQuery->filterByTestColumn([1, 2, 3], Criteria::NOT_IN);
    }
}
