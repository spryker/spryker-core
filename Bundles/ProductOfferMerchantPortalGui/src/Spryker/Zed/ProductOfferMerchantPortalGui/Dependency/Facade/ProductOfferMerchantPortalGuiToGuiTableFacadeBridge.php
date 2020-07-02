<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;

class ProductOfferMerchantPortalGuiToGuiTableFacadeBridge implements ProductOfferMerchantPortalGuiToGuiTableFacadeInterface
{
    /**
     * @var \Spryker\Zed\GuiTable\Business\GuiTableFacadeInterface $guiTableFacade
     */
    protected $guiTableFacade;

    /**
     * @param \Spryker\Zed\GuiTable\Business\GuiTableFacadeInterface $guiTableFacade
     */
    public function __construct($guiTableFacade)
    {
        $this->guiTableFacade = $guiTableFacade;
    }

    /**
     * @param array $requestParams
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataRequestTransfer
     */
    public function buildGuiTableDataRequest(
        array $requestParams,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableDataRequestTransfer {
        return $this->guiTableFacade->buildGuiTableDataRequest($requestParams, $guiTableConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    public function formatGuiTableDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array {
        return $this->guiTableFacade->formatGuiTableDataResponse($guiTableDataResponseTransfer, $guiTableConfigurationTransfer);
    }
}
