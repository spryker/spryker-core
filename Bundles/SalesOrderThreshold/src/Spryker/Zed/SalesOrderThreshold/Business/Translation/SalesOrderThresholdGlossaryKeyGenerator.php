<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Translation;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;

class SalesOrderThresholdGlossaryKeyGenerator implements SalesOrderThresholdGlossaryKeyGeneratorInterface
{
    protected const SALES_ORDER_THRESHOLD_GLOSSARY_PREFIX = 'sales-order-threshold';
    protected const SALES_ORDER_THRESHOLD_GLOSSARY_MESSAGE = 'message';

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function assignMessageGlossaryKey(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): SalesOrderThresholdTransfer {
        $this->assertRequiredTransferAttributes($salesOrderThresholdTransfer);

        $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->setMessageGlossaryKey(
            $this->generateMessageGlossaryKey($salesOrderThresholdTransfer)
        );

        return $salesOrderThresholdTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return string
     */
    protected function generateMessageGlossaryKey(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): string
    {
        return strtolower(implode(
            '.',
            [
                static::SALES_ORDER_THRESHOLD_GLOSSARY_PREFIX,
                $salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getKey(),
                $salesOrderThresholdTransfer->getStore()->getName(),
                $salesOrderThresholdTransfer->getCurrency()->getCode(),
                static::SALES_ORDER_THRESHOLD_GLOSSARY_MESSAGE,
            ]
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return void
     */
    protected function assertRequiredTransferAttributes(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): void
    {
        $salesOrderThresholdTransfer->getSalesOrderThresholdValue()
            ->requireSalesOrderThresholdType()
            ->getSalesOrderThresholdType()
            ->requireThresholdGroup();

        $salesOrderThresholdTransfer->getStore()
            ->requireName();

        $salesOrderThresholdTransfer->getCurrency()
            ->requireCode();
    }
}
