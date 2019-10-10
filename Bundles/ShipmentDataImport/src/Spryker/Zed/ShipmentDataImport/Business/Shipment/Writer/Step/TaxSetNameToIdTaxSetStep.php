<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\Step;

use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\DataSet\ShipmentDataSetInterface;

class TaxSetNameToIdTaxSetStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $taxSetName = $dataSet[ShipmentDataSetInterface::COL_TAX_SET_NAME];

        if (!$taxSetName) {
            throw new EntityNotFoundException(sprintf('Invalid tax set name'));
        }

        $taxSetEntity = SpyTaxSetQuery::create()
            ->filterByName($taxSetName)
            ->findOneOrCreate();

        if ($taxSetEntity->isNew()) {
            $taxSetEntity->save();
        }

        $dataSet[ShipmentDataSetInterface::COL_ID_TAX_SET] = $taxSetEntity->getIdTaxSet();
    }
}
