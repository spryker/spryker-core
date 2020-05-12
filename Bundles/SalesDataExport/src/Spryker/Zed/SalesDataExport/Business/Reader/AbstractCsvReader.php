<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business\Reader;

use Spryker\Zed\SalesDataExport\Persistence\SalesDataExportRepositoryInterface;

abstract class AbstractCsvReader implements CsvReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportRepositoryInterface
     */
    protected $salesDataExportRepository;

    /**
     * @param \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportRepositoryInterface $salesDataExportRepository
     */
    public function __construct(SalesDataExportRepositoryInterface $salesDataExportRepository)
    {
        $this->salesDataExportRepository = $salesDataExportRepository;
    }

    /**
     * @param array $fields
     * @param array $exportData
     * @param int $offset
     *
     * @return array
     */
    protected function formatExportData(array $fields, array $exportData, int $offset): array
    {
        $exportData = array_map(function (array $exportRow) use ($fields): array {
            return array_merge(array_flip($fields), $exportRow);
        }, $exportData);

        if ($offset === 0) {
            array_unshift($exportData, $fields);
        }

        return $exportData;
    }
}
