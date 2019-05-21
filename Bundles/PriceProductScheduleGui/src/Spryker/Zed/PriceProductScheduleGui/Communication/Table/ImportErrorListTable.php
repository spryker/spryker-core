<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ImportErrorListTable extends AbstractTable
{
    /**
     * @var \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    protected $priceProductScheduleListImportResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer $priceProductScheduleListImportResponseTransfer
     */
    public function __construct(
        PriceProductScheduleListImportResponseTransfer $priceProductScheduleListImportResponseTransfer
    ) {
        $this->priceProductScheduleListImportResponseTransfer = $priceProductScheduleListImportResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->disableSearch();

        $config->setHeader([
            PriceProductScheduleImportTransfer::ROW_NUMBER => 'Row n°',
            PriceProductScheduleListImportErrorTransfer::MESSAGE => 'Error',
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $data = [];

        foreach ($this->priceProductScheduleListImportResponseTransfer->getErrors() as $error) {
            $data[] = [
                PriceProductScheduleImportTransfer::ROW_NUMBER => $error->getPriceProductScheduleImport()->getRowNumber(),
                PriceProductScheduleListImportErrorTransfer::MESSAGE => $error->getMessage(),
            ];
        }

        return $data;
    }
}
