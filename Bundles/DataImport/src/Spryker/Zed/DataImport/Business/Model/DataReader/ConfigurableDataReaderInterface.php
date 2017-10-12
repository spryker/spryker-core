<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataReader;

use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;

interface ConfigurableDataReaderInterface
{
    /**
     * Specification:
     * - Re-configures the DataReaderInterface.
     *
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImportReaderConfigurationTransfer
     *
     * @return $this
     */
    public function configure(DataImporterReaderConfigurationTransfer $dataImportReaderConfigurationTransfer);
}
