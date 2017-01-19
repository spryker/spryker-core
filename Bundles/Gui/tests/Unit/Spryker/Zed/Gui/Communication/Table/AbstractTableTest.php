<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Gui\Communication\Table;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Unit\Spryker\Zed\Gui\Communication\Fixture\FooTable;
use Unit\Spryker\Zed\Kernel\Communication\Fixtures\AbstractPlugin\Plugin\FooPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Gui
 * @group Communication
 * @group Table
 * @group AbstractTableTest
 */
class AbstractTableTest extends PHPUnit_Framework_TestCase
{

    const COL_ONE = 'one';
    const COL_TWO = 'two';

    /**
     * @var \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    protected $table;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->table = new FooTable();
    }

    /**
     * @return void
     */
    public function testX()
    {
        $config = new TableConfiguration();
        $config->setSortable([
           static::COL_ONE,
           static::COL_TWO
        ]);

        $config->setDefaultSortField($config);

        $result = $this->table->getOrders($config);

        dd($result);
        $this->assertTrue(true);
    }

}
