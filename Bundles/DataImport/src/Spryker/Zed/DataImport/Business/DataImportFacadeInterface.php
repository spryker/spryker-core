<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportBusinessFactory getFactory()
 */
interface DataImportFacadeInterface
{
    /**
     * Specification:
     * - Creates importer.
     * - Runs `DataImporterInterface::import()`.
     * - Returns DataImportReportTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfiguration
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfiguration = null);

    /**
     * Specification:
     * - Returns a list of all applied `DataImportPluginInterfaces` and `DataImportInterfaces`.
     *
     * @api
     *
     * @return array
     */
    public function listImporters(): array;

    /**
     * Specification:
     * - Triggers publish event for all imported entities.
     *
     * @api
     *
     * @return void
     */
    public function publish(): void;
}
