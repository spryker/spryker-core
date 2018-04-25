<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\ProductBarcodeDataImport\Business\Model\ProductBarcodeWriterStep;

/**
 * @method \Spryker\Zed\ProductBarcodeDataImport\ProductBarcodeDataImportConfig getConfig()
 */
class ProductBarcodeDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductBarcodeDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductBarcodeDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new ProductBarcodeWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }
}
