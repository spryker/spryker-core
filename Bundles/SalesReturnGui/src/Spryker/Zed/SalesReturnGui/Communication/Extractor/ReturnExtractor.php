<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Extractor;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig;

class ReturnExtractor implements ReturnExtractorInterface
{
    protected const DEFAULT_LABEL_CLASS = 'label-default';

    /**
     * @var \Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig
     */
    protected $salesReturnGuiConfig;

    /**
     * @param \Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig $salesReturnGuiConfig
     */
    public function __construct(SalesReturnGuiConfig $salesReturnGuiConfig)
    {
        $this->salesReturnGuiConfig = $salesReturnGuiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string[]
     */
    public function extractUniqueOrderReferencesFromReturn(ReturnTransfer $returnTransfer): array
    {
        $uniqueOrderReferences = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $idSalesOrder = $returnItemTransfer->getOrderItem()->getFkSalesOrder();
            $orderReference = $returnItemTransfer->getOrderItem()->getOrderReference();

            $uniqueOrderReferences[$idSalesOrder] = $orderReference;
        }

        return $uniqueOrderReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return string[]
     */
    public function extractUniqueItemStateLabelsFromReturn(ReturnTransfer $returnTransfer): array
    {
        $uniqueItemStates = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $state = $returnItemTransfer->getOrderItem()->getState()->getName();

            $uniqueItemStates[$state] = $this->salesReturnGuiConfig->getItemStateToLabelClassMapping()[$state] ?? static::DEFAULT_LABEL_CLASS;
        }

        return $uniqueItemStates;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return int[]
     */
    public function extractSalesOrderItemIdsFromReturn(ReturnTransfer $returnTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $salesOrderItemIds[] = $returnItemTransfer->getOrderItem()->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }
}
