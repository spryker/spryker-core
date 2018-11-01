<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Helper;

use Codeception\Module;
use Countable;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class DataReaderHelper extends Module
{
    /**
     * @param int $expectedRow
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function assertDataSetWithKeys($expectedRow, DataSetInterface $dataSet)
    {
        $dataSetWithKeys = $this->getDataSetWithKeys($expectedRow);
        $this->assertEquals(new DataSet($dataSetWithKeys), $dataSet);
    }

    /**
     * @param int $expectedRow
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function assertDataSetWithoutKeys($expectedRow, DataSetInterface $dataSet)
    {
        $dataSetWithKeys = $this->getDataSetWithKeys($expectedRow);
        $this->assertEquals(new DataSet(array_values($dataSetWithKeys)), $dataSet);
    }

    /**
     * @param int $expectedNumberOfDataSets
     * @param \Countable $reader
     *
     * @return void
     */
    public function assertDataSetCount($expectedNumberOfDataSets, Countable $reader)
    {
        $givenCount = $reader->count();
        $this->assertSame($expectedNumberOfDataSets, $givenCount, sprintf(
            'Expected "%s" data sets found "%s".',
            $expectedNumberOfDataSets,
            $givenCount
        ));
    }

    /**
     * @param int $expectedRow
     *
     * @return array
     */
    private function getDataSetWithKeys($expectedRow)
    {
        return [
            'column1' => 'value-1-row-' . $expectedRow,
            'column2' => 'value-2-row-' . $expectedRow,
            'column3' => 'value-3-row-' . $expectedRow,
        ];
    }
}
