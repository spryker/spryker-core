<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Http;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface GuiTableDataRequestHandlerInterface
{
    /**
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface $guiTableDataProvider
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleGetDataRequest(
        Request $request,
        GuiTableDataProviderInterface $guiTableDataProvider,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        LocaleTransfer $localeTransfer
    ): Response;
}
