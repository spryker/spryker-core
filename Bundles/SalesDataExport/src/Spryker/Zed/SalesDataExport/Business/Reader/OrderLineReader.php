<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business\Reader;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Spryker\Zed\SalesDataExport\Persistence\SalesDataExportRepositoryInterface;

class OrderLineReader implements LineReaderInterface
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
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return string[][]
     */
    public function lineReadBatch(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit): array
    {
        return $this->salesDataExportRepository->getOrderData($dataExportConfigurationTransfer, $offset, $limit);
    }
}
