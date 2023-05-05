<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business\Validator;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

interface DataSetValidatorInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function assertNoEmptyColumns(DataSetInterface $dataSet): void;
}
