<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNoteMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;

class MerchantOrderItemTableExpander implements MerchantOrderItemTableExpanderInterface
{
    protected const COL_KEY_CART_NOTE = 'cartNote';

    /**
     * @uses \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface::COLUMN_TYPE_TEXT
     */
    protected const COLUMN_TYPE_TEXT = 'text';

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function expandConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId(static::COL_KEY_CART_NOTE)
            ->setTitle('Comment')
            ->setType(static::COLUMN_TYPE_TEXT)
            ->setSortable(false)
            ->setHideable(false);

        $guiTableConfigurationTransfer->addColumn($guiTableColumnConfigurationTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expandDataResponse(GuiTableDataResponseTransfer $guiTableDataResponseTransfer): GuiTableDataResponseTransfer
    {
        foreach ($guiTableDataResponseTransfer->getRows() as $guiTableRowDataResponseTransfer) {
            $responseData = $guiTableRowDataResponseTransfer->getResponseData();

            $responseData[static::COL_KEY_CART_NOTE] = $guiTableRowDataResponseTransfer->requirePayload()
                ->getPayload()
                ->requireItem()
                ->getItem()
                ->getCartNote();

            $guiTableRowDataResponseTransfer->setResponseData($responseData);
        }

        return $guiTableDataResponseTransfer;
    }
}
