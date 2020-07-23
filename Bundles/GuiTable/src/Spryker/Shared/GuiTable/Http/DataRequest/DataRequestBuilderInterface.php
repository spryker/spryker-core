<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Http\DataRequest;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Symfony\Component\HttpFoundation\Request;

interface DataRequestBuilderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    public function buildGuiTableDataRequestFromRequest(
        Request $request,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        LocaleTransfer $localeTransfer
    ): GuiTableDataRequestTransfer;
}
