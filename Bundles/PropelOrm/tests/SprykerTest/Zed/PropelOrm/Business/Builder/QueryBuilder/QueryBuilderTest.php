<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Builder\QueryBuilder;

use Codeception\Test\Unit;
use Propel\Generator\Builder\Om\AbstractOMBuilder;
use Propel\Generator\Builder\Om\ObjectBuilder;
use Propel\Generator\Builder\Om\TableMapBuilder;
use Propel\Generator\Config\QuickGeneratorConfig;
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
     * @return array
     */
    protected function getFilesToGenerate()
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
    protected function _before()
    {
        $config = new QuickGeneratorConfig();
        $table = new Table('Foo');
        $column = new Column('testColumn', PropelTypes::INTEGER);
        $table->addColumn($column);
        $table->setNamespace('SprykerTest\Zed\PropelOrm\Business\Builder\QueryBuilder');
        $table->setDatabase(new Database('TestDB', new DefaultPlatform()));

        foreach ($this->getFilesToGenerate() as $fileName => $builderClass) {
            $builder = new $builderClass($table);
            $builder->setGeneratorConfig($config);
            $this->writePropelFile($builder, $fileName);
        }
    }

    /**
     * @return void
     */
    protected function _after()
    {
        foreach (array_keys($this->getFilesToGenerate()) as $fileName) {
            $this->deletePropelFile($fileName);
        }
    }

    /**
     * @return void
     */
    public function testGeneratedFilterFunctionDoesNotThrowExceptionOnNotIn()
    {
        $testQuery = new FooQuery();
        $testQuery->filterByTestColumn([1, 2, 3], Criteria::NOT_IN);
    }

    /**
     * @param \Propel\Generator\Builder\Om\AbstractOMBuilder $queryBuilder
     * @param string $fileName
     *
     * @return void
     */
    protected function writePropelFile(AbstractOMBuilder $queryBuilder, $fileName)
    {
        $fileContent = $queryBuilder->build();
        $directory = dirname($fileName);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($fileName, $fileContent);
    }

    /**
     * @param string $fileName
     *
     * @return void
     */
    protected function deletePropelFile($fileName)
    {
        unlink($fileName);
    }
}
