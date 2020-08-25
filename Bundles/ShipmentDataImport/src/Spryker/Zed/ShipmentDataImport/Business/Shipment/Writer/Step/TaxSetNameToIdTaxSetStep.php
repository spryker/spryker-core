<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\Step;

use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\DataSet\ShipmentDataSetInterface;

class TaxSetNameToIdTaxSetStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idTaxSetCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $taxSetName = $dataSet[ShipmentDataSetInterface::COL_TAX_SET_NAME];

        if (!$taxSetName) {
            throw new DataKeyNotFoundInDataSetException('Tax set name is missing');
        }

        if (!isset(static::$idTaxSetCache[$taxSetName])) {
            $taxSetEntity = SpyTaxSetQuery::create()
                ->filterByName($taxSetName)
                ->findOneOrCreate();
            $taxSetEntity->save();

            static::$idTaxSetCache[$taxSetName] = $taxSetEntity->getIdTaxSet();
        }

        $dataSet[ShipmentDataSetInterface::COL_ID_TAX_SET] = static::$idTaxSetCache[$taxSetName];
    }
}
