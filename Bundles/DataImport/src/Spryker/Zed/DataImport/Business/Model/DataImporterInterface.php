<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;

interface DataImporterInterface
{

    /**
     * Specification:
     * - Reads data from an DataReaderInterface.
     * - Executes hook before all DataSetHandlerInterfaces are executed.
     * - Iterates over DataReaderInterface.
     * // Belongs to DataSetHandlerInterface - Executes hook before DataSetHandlerInterface is executed.
     * - Iterates of over all applied DataSetHandlerInterfaces
     *   and passes DataSetInterface retrieved from the DataReaderInterface to it.
     * // Belongs to DataSetHandlerInterface - Executes hook after DataSetHandlerInterface is executed.
     * - Executes hook after all DataSetHandlerInterfaces are executed.
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null);

    /**
     * Specification:
     * - Returns the name of the DataImporter.
     *   Examples: `full`, `categories`, `products`, `product-to-category` etc.
     *
     * @return string
     */
    public function getImportType();

}
