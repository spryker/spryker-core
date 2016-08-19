<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\EventJournal\Model\Filter;

use Spryker\Shared\EventJournal\Model\Event;
use Spryker\Shared\EventJournal\Model\Filter\RecursiveFieldFilter;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group EventJournal
 * @group Model
 * @group Filter
 * @group RecursiveFieldFilterTest
 */
class RecursiveFieldFilterTest extends \PHPUnit_Framework_TestCase
{

    const FIELD_KEY = 'key';

    const KEY_DO_NOT_FILTER = 'do not filter';
    const KEY_DO_FILTER = 'do filter';

    const VALUE_NOT_FILTERED = 'not filtered';
    const VALUE_FILTERED = '***';

    /**
     * @return void
     */
    public function testFilterShouldFilterArray()
    {
        $event = new Event();
        $data = [
            self::KEY_DO_FILTER => self::VALUE_NOT_FILTERED,
        ];
        $event->setField(self::FIELD_KEY, $data);

        $filter = $this->getRecursiveFieldFilter();
        $filter->filter($event);

        $filteredData = $event->getFields()[self::FIELD_KEY];
        $expectedData = [
            self::KEY_DO_FILTER => self::VALUE_FILTERED,
        ];

        $this->assertSame($expectedData, $filteredData);
    }

    /**
     * @return void
     */
    public function testFilterShouldFilterArrayRecursive()
    {
        $event = new Event();
        $data = [
            self::KEY_DO_FILTER => self::VALUE_NOT_FILTERED,
            [
                self::KEY_DO_FILTER => self::VALUE_NOT_FILTERED,
            ],
            self::KEY_DO_NOT_FILTER => self::VALUE_NOT_FILTERED,
        ];
        $event->setField(self::FIELD_KEY, $data);

        $filter = $this->getRecursiveFieldFilter();
        $filter->filter($event);

        $filteredData = $event->getFields()[self::FIELD_KEY];
        $expectedData = [
            self::KEY_DO_FILTER => self::VALUE_FILTERED,
            [
                self::KEY_DO_FILTER => self::VALUE_FILTERED,
            ],
            self::KEY_DO_NOT_FILTER => self::VALUE_NOT_FILTERED,
        ];

        $this->assertSame($expectedData, $filteredData);
    }

    /**
     * @return void
     */
    public function testFilterShouldNotFilterArray()
    {
        $event = new Event();
        $data = [
            self::KEY_DO_NOT_FILTER => self::VALUE_NOT_FILTERED,
        ];
        $event->setField(self::FIELD_KEY, $data);

        $filter = $this->getRecursiveFieldFilter();
        $filter->filter($event);

        $filteredData = $event->getFields()[self::FIELD_KEY];
        $expectedData = [
            self::KEY_DO_NOT_FILTER => self::VALUE_NOT_FILTERED,
        ];

        $this->assertSame($expectedData, $filteredData);
    }

    /**
     * @return void
     */
    public function testFilterShouldFilterString()
    {
        $event = new Event();

        $event->setField(self::FIELD_KEY, self::VALUE_NOT_FILTERED);

        $options = [
            RecursiveFieldFilter::OPTION_FILTER_PATTERN => [
                [self::FIELD_KEY, self::FIELD_KEY, self::KEY_DO_FILTER],
            ],
            RecursiveFieldFilter::OPTION_FILTERED_STR => self::VALUE_FILTERED,
        ];
        $filter = new RecursiveFieldFilter($options);
        $filter->filter($event);

        $filteredData = $event->getFields()[self::FIELD_KEY];
        $expectedData = self::VALUE_FILTERED;

        $this->assertSame($expectedData, $filteredData);
    }

    /**
     * @return \Spryker\Shared\EventJournal\Model\Filter\RecursiveFieldFilter
     */
    private function getRecursiveFieldFilter()
    {
        $options = [
            RecursiveFieldFilter::OPTION_FILTER_PATTERN => [
                [self::FIELD_KEY, self::KEY_DO_FILTER],
            ],
            RecursiveFieldFilter::OPTION_FILTERED_STR => self::VALUE_FILTERED,
        ];
        $filter = new RecursiveFieldFilter($options);
        return $filter;
    }

}
