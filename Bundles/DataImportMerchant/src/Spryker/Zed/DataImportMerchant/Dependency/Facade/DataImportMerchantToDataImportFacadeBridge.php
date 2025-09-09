<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchant\Dependency\Facade;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

class DataImportMerchantToDataImportFacadeBridge implements DataImportMerchantToDataImportFacadeInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\DataImportFacadeInterface
     */
    protected $dataImportFacade;

    /**
     * @param \Spryker\Zed\DataImport\Business\DataImportFacadeInterface $dataImportFacade
     */
    public function __construct($dataImportFacade)
    {
        $this->dataImportFacade = $dataImportFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importByAction(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer,
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->dataImportFacade->importByAction(
            $dataImportConfigurationActionTransfer,
            $dataImporterConfigurationTransfer,
        );
    }
}
