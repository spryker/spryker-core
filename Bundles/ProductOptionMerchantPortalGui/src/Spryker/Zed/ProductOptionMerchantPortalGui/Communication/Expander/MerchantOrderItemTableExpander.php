<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;

class MerchantOrderItemTableExpander implements MerchantOrderItemTableExpanderInterface
{
    /**
     * @var string
     */
    protected const COL_KEY_PRODUCT_OPTIONS = 'productOptions';

    /**
     * @uses \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface::COLUMN_TYPE_LIST
     *
     * @var string
     */
    protected const COLUMN_TYPE_LIST = 'list';

    /**
     * @uses \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface::COLUMN_TYPE_TEXT
     *
     * @var string
     */
    protected const COLUMN_TYPE_TEXT = 'text';

    /**
     * @var int
     */
    protected const LIST_TYPE_OPTION_VALUE_LIMIT = 1000;

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function expandConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId(static::COL_KEY_PRODUCT_OPTIONS)
            ->setTitle('Options')
            ->setType(static::COLUMN_TYPE_LIST)
            ->setSortable(false)
            ->setHideable(true)
            ->addTypeOption('type', static::COLUMN_TYPE_TEXT)
            ->addTypeOption('limit', static::LIST_TYPE_OPTION_VALUE_LIMIT);

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

            $responseData[static::COL_KEY_PRODUCT_OPTIONS] = $this->getProductOptionsArray($guiTableRowDataResponseTransfer);

            $guiTableRowDataResponseTransfer->setResponseData($responseData);
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableRowDataResponseTransfer $guiTableRowDataResponseTransfer
     *
     * @return array<string>
     */
    protected function getProductOptionsArray(GuiTableRowDataResponseTransfer $guiTableRowDataResponseTransfer): array
    {
        $productOptionTransfers = $guiTableRowDataResponseTransfer->requirePayload()
            ->getPayload()
            ->requireItem()
            ->getItem()
            ->getProductOptions();

        $productOptionsArray = [];
        foreach ($productOptionTransfers as $productOptionTransfer) {
            $productOptionsArray[] = $productOptionTransfer->getValue();
            $productOptionsArray[] = 'SKU: ' . $productOptionTransfer->getSku();
        }

        return $productOptionsArray;
    }
}
