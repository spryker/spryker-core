<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataSet;

use Codeception\TestCase\Test;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
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
class DataSetTest extends Test
{

    /**
     * @return void
     */
    public function testGetValueWithUndefinedKeyThrowsException()
    {
        $dataSet = new DataSet();
        $this->expectException(DataKeyNotFoundInDataSetException::class);
        $dataSet['undefinedKey'];
    }

    /**
     * @return void
     */
    public function testUnsetValueWithUndefinedKeyThrowsException()
    {
        $dataSet = new DataSet();
        $this->expectException(DataKeyNotFoundInDataSetException::class);
        unset($dataSet['undefinedKey']);
    }

    /**
     * @return void
     */
    public function testExchangeArraySetsNewArray()
    {
        $oldData = ['old'];
        $newData = ['new'];

        $dataSet = new DataSet($oldData);
        $return = $dataSet->exchangeArray($newData);
        $this->assertSame($oldData, $return);
        $this->assertSame($newData, $dataSet->getArrayCopy());
    }

}
