<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Business\RequestBuilder;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;

interface DataRequestBuilderInterface
{
    /**
     * @param mixed[] $requestParams
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    public function buildGuiTableDataRequest(
        array $requestParams,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableDataRequestTransfer;
}
