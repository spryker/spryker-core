<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataReader\FileResolver;

use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Exception\FileResolverFileNotFoundException;

class FileResolver implements FileResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\FileResolverFileNotFoundException
     *
     * @return string
     */
    public function resolveFile(DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer): string
    {
        $fileNames = $this->buildFileNames($dataImporterReaderConfigurationTransfer);

        foreach ($fileNames as $fileName) {
            if ($this->isValid($fileName)) {
                return $fileName;
            }
        }

        throw new FileResolverFileNotFoundException(sprintf(
            'Could not find file "%s". Checked path(s): %s',
            $dataImporterReaderConfigurationTransfer->getFileName(),
            implode(', ', $fileNames)
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
     *
     * @return array
     */
    protected function buildFileNames(DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer): array
    {
        $fileName = $dataImporterReaderConfigurationTransfer->getFileName();
        $fileNames = [];
        $fileNames[] = $fileName;

        foreach ($dataImporterReaderConfigurationTransfer->getDirectories() as $directory) {
            $fileNames[] = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;
        }

        return $fileNames;
    }

    /**
     * @param string $fileName
     *
     * @return bool
     */
    protected function isValid(string $fileName): bool
    {
        return (is_file($fileName) && is_readable($fileName));
    }
}
