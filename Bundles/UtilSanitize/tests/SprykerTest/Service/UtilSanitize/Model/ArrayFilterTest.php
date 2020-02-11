<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilSanitize\Model;

use Codeception\Test\Unit;
use Spryker\Service\UtilSanitize\Model\ArrayFilter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilSanitize
 * @group Model
 * @group ArrayFilterTest
 * Add your own group annotations below this line
 */
class ArrayFilterTest extends Unit
{
    /**
     * @var \SprykerTest\Service\UtilSanitize\UtilSanitizeServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testArrayFilterRecursive(): void
    {
        //Arrange
        $arrayFilter = $this->createArrayFilterModel();
        $array = $this->tester->getArrayToFilter();
        $expected = $this->tester->getArrayFilterRecursiveExpectedArray($array);

        //Act
        $result = $arrayFilter->arrayFilterRecursive($array);

        //Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @return void
     */
    public function testFilterOutBlankValuesRecursively(): void
    {
        //Arrange
        $arrayFilter = $this->createArrayFilterModel();
        $array = $this->tester->getArrayToFilter();
        $expected = $this->tester->getFilterOutBlankValuesRecursivelyExpectedArray($array);

        //Act
        $result = $arrayFilter->filterOutBlankValuesRecursively($array);

        //Assert
        $this->assertSame($expected, $result);
    }

    /**
     * @return \Spryker\Service\UtilSanitize\Model\ArrayFilter
     */
    protected function createArrayFilterModel(): ArrayFilter
    {
        return new ArrayFilter();
    }
}
