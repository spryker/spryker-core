<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Gui\Communication\Table;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Symfony\Component\HttpFoundation\Request;
use Unit\Spryker\Zed\Gui\Communication\Fixture\FooTable;

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

        $request = new Request();
        $this->table->setRequest($request);
    }

    /**
     * @return void
     */
    public function testGetOrdersDefault()
    {
        $config = new TableConfiguration();
        $config->setHeader([
            static::COL_ONE => 'One',
            static::COL_TWO => 'Two',
        ]);
        $config->setSortable([
            static::COL_ONE,
            static::COL_TWO,
        ]);

        $result = $this->table->getOrders($config);
        $expected = [
            [
                'column' => 0,
                'dir' => 'asc',
            ],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @return void
     */
    public function testGetOrdersWithCustomSortField()
    {
        $config = new TableConfiguration();
        $config->setHeader([
            static::COL_ONE => 'One',
            static::COL_TWO => 'Two',
        ]);
        $config->setSortable([
           static::COL_ONE,
           static::COL_TWO,
        ]);

        $config->setDefaultSortField(static::COL_TWO);

        $result = $this->table->getOrders($config);
        $expected = [
            [
              'column' => 1,
              'dir' => 'asc',
            ],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @return void
     */
    public function testGetOrdersWithCustomSortFieldAndCustomDirection()
    {
        $config = new TableConfiguration();
        $config->setHeader([
            static::COL_ONE => 'One',
            static::COL_TWO => 'Two',
        ]);
        $config->setSortable([
            static::COL_ONE,
            static::COL_TWO,
        ]);

        $config->setDefaultSortField(static::COL_TWO, TableConfiguration::SORT_DESC);

        $result = $this->table->getOrders($config);
        $expected = [
            [
                'column' => 1,
                'dir' => 'desc',
            ],
        ];
        $this->assertSame($expected, $result);
    }

}
