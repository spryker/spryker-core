<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Dump;

use Generated\Shared\Transfer\DataImportConfigurationTransfer;

interface ImporterDumperInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\DataImport\Business\Model\Dump\ImporterDumperInterface::getImportersDumpByConfiguration()} instead.
     *
     * @return string[]
     */
    public function dump(): array;

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationTransfer $dataImportConfigurationTransfer
     *
     * @return string[]
     */
    public function getImportersDumpByConfiguration(DataImportConfigurationTransfer $dataImportConfigurationTransfer): array;
}
