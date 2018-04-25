<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

/**
 * TODO: Fix "Call to undefined method Spryker\Zed\ProductBarcodeDataImport\Business\ProductBarcodeDataImportFacade::getFactory()"
 *
 * @method \Spryker\Zed\ProductBarcodeDataImport\Business\ProductBarcodeDataImportBusinessFactory getFactory()
 */
class ProductBarcodeDataImportFacade implements ProductBarcodeDataImportFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFactory()->createProductBarcodeDataImport()->import($dataImporterConfigurationTransfer);
    }
}
