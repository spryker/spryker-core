<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductBarcodeDataImport\ProductBarcodeDataImportConfig;

/**
 * @method \Spryker\Zed\ProductBarcodeDataImport\Business\ProductBarcodeDataImportFacadeInterface getFacade()
 */
class ProductBarcodeDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        return $this->getFacade()->import($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getImportType()
    {
        return ProductBarcodeDataImportConfig::IMPORT_TYPE_PRODUCT_BARCODE;
    }
}
