<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataReader\FileResolver;

use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;

interface FileResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\FileResolverFileNotFoundException
     *
     * @return string
     */
    public function resolveFile(DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer): string;
}
