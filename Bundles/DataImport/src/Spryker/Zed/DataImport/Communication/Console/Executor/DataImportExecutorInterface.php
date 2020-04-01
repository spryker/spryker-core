<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console\Executor;

use Generated\Shared\Transfer\DataImporterReportTransfer;
use Symfony\Component\Console\Input\InputInterface;

interface DataImportExecutorInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\DataImport\Communication\Console\Executor\DataImportExecutorInterface::executeByConfigAndImporterType()} instead.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $importerType
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function executeByImporterType(InputInterface $input, string $importerType): DataImporterReportTransfer;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $configPath
     * @param string|null $importerType
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function executeByConfigAndImporterType(InputInterface $input, string $configPath, ?string $importerType): DataImporterReportTransfer;
}
