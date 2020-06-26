<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Business;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;

interface GuiTableFacadeInterface
{
    /**
     * Specification:
     * - Returns GuiTableDataRequestTransfer based on provider request parameters and table configuration.
     * - Sets default sort direction, page and page size if they are not provided.
     * - Sets Locale ID from current Locale.
     *
     * @api
     *
     * @param mixed[] $requestParams
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    public function buildGuiTableDataRequest(
        array $requestParams,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableDataRequestTransfer;

    /**
     * Specification:
     * - Formats provider GuiTableDataResponseTransfer to array.
     * - Formats column values by executing ResponseColumnValueFormatterPluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return mixed[]
     */
    public function formatGuiTableDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array;
}
