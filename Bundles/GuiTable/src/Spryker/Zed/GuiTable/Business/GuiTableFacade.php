<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Business;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GuiTable\Business\GuiTableBusinessFactory getFactory()
 */
class GuiTableFacade extends AbstractFacade implements GuiTableFacadeInterface
{
    /**
     * {@inheritDoc}
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
    ): GuiTableDataRequestTransfer {
        return $this->getFactory()
            ->createDataRequestBuilder()
            ->buildGuiTableDataRequest($requestParams, $guiTableConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
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
    ): array {
        return $this->getFactory()
            ->createDataResponseFormatter()
            ->formatGuiTableDataResponse($guiTableDataResponseTransfer, $guiTableConfigurationTransfer);
    }
}
