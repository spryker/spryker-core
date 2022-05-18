<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataSet;

use Codeception\Test\Unit;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataSet
 * @group DataSetTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class DataSetTest extends Unit
{
    /**
     * @return void
     */
    public function testGetValueWithUndefinedKeyThrowsException(): void
    {
        $dataSet = $this->tester->getFactory()->createDataSet();
        $this->expectException(DataKeyNotFoundInDataSetException::class);
        $dataSet['undefinedKey'];
    }

    /**
     * @return void
     */
    public function testUnsetValueWithUndefinedKeyThrowsException(): void
    {
        $dataSet = $this->tester->getFactory()->createDataSet();
        $this->expectException(DataKeyNotFoundInDataSetException::class);
        unset($dataSet['undefinedKey']);
    }

    /**
     * @return void
     */
    public function testExchangeArraySetsNewArray(): void
    {
        $oldData = ['old'];
        $newData = ['new'];

        $dataSet = $this->tester->getFactory()->createDataSet($oldData);
        $return = $dataSet->exchangeArray($newData);
        $this->assertSame($oldData, $return);
        $this->assertSame($newData, $dataSet->getArrayCopy());
    }

    /**
     * @return void
     */
    public function testCheckIsSetValue(): void
    {
        $dataSet = $this->tester->getFactory()->createDataSet([]);
        $dataSet['null'] = null;
        $dataSet['true'] = true;
        $dataSet['false'] = false;
        $dataSet['emptystring'] = '';
        $dataSet['int'] = 0;

        $this->assertFalse(isset($dataSet['null']));
        $this->assertTrue(isset($dataSet['true']));
        $this->assertTrue(isset($dataSet['false']));
        $this->assertTrue(isset($dataSet['emptystring']));
        $this->assertTrue(isset($dataSet['int']));
    }

    /**
     * @return void
     */
    public function testCheckEmptyValue(): void
    {
        $dataSet = $this->tester->getFactory()->createDataSet([]);
        $dataSet['null'] = null;
        $dataSet['true'] = true;
        $dataSet['false'] = false;
        $dataSet['emptystring'] = '';
        $dataSet['int'] = 0;

        $this->assertTrue(empty($dataSet['null']));
        $this->assertFalse(empty($dataSet['true']));
        $this->assertTrue(empty($dataSet['false']));
        $this->assertTrue(empty($dataSet['emptystring']));
        $this->assertTrue(empty($dataSet['int']));
    }
}
