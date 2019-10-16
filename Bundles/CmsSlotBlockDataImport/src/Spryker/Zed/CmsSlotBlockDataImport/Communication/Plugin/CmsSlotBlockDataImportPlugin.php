<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\CmsSlotBlockDataImport\src\Spryker\Zed\CmsSlotBlockDataImport\Communication\Plugin;


use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\CmsSlotBlockDataImport\src\Spryker\Zed\CmsSlotBlockDataImport\CmsSlotBlockDataImportConfig;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;

/**
 * @method \Spryker\CmsSlotBlockDataImport\src\Spryker\Zed\CmsSlotBlockDataImport\Business\CmsSlotBlockDataImportFacadeInterface getFacade()
 */
class CmsSlotBlockDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()->importCmsSlotBlock($dataImporterConfigurationTransfer);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getImportType()
    {
        return CmsSlotBlockDataImportConfig::IMPORT_TYPE_CMS_SLOT_BLOCK;
    }
}
