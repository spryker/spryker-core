<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Http\DataResponse;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface;

class DataResponseFormatter implements DataResponseFormatterInterface
{
    protected const KEY_DATA_RESPONSE_ARRAY_DATA = 'data';

    /**
     * @var \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(GuiTableToUtilDateTimeServiceInterface $utilDateTimeService)
    {
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return mixed[]
     */
    public function formatGuiTableDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array {
        $guiTableDataResponseArray = $guiTableDataResponseTransfer->toArray(true, true);
        $guiTableDataResponseArray[static::KEY_DATA_RESPONSE_ARRAY_DATA] = $this->formatValues(
            $guiTableDataResponseArray,
            $guiTableConfigurationTransfer
        );

        unset($guiTableDataResponseArray[GuiTableDataResponseTransfer::ROWS]);

        return $guiTableDataResponseArray;
    }

    /**
     * @param mixed[] $guiTableDataResponseArray
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return mixed[]
     */
    protected function formatValues(
        array $guiTableDataResponseArray,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array {
        $rows = $guiTableDataResponseArray[static::KEY_DATA_RESPONSE_ARRAY_DATA] = array_map(function (array $rowData): array {
            return $rowData[GuiTableRowDataResponseTransfer::RESPONSE_DATA];
        }, $guiTableDataResponseArray[GuiTableDataResponseTransfer::ROWS]);

        $indexedColumnTypes = $this->getIndexedColumnTypesByColumnIds($guiTableConfigurationTransfer);

        foreach ($rows as $rowKey => $row) {
            foreach ($row as $idColumn => $value) {
                if (isset($indexedColumnTypes[$idColumn]) && $indexedColumnTypes[$idColumn] === GuiTableConfigurationBuilderInterface::COLUMN_TYPE_DATE) {
                    $rows[$rowKey][$idColumn] = $value ? $this->utilDateTimeService->formatDateTimeToIso8601($value) : null;
                }
            }
        }

        return $rows;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return string[]
     */
    protected function getIndexedColumnTypesByColumnIds(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $indexedColumnTypes = [];
        foreach ($guiTableConfigurationTransfer->getColumns() as $guiTableColumnConfigurationTransfer) {
            $indexedColumnTypes[$guiTableColumnConfigurationTransfer->getId()] = $guiTableColumnConfigurationTransfer->getType();
        }

        return $indexedColumnTypes;
    }
}
