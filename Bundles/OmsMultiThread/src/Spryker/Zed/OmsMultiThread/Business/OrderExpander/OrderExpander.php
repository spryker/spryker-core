<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsMultiThread\Business\OrderExpander;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\OmsMultiThread\Business\OmsProcessor\OmsProcessorIdGeneratorInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\OmsMultiThread\Business\OmsProcessor\OmsProcessorIdGeneratorInterface
     */
    protected $omsProcessorIdGenerator;

    /**
     * @param \Spryker\Zed\OmsMultiThread\Business\OmsProcessor\OmsProcessorIdGeneratorInterface $omsProcessorIdGenerator
     */
    public function __construct(OmsProcessorIdGeneratorInterface $omsProcessorIdGenerator)
    {
        $this->omsProcessorIdGenerator = $omsProcessorIdGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function expandSalesOrderEntityTransferWithOmsProcessorIdentifier(
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer,
        QuoteTransfer $quoteTransfer
    ): SpySalesOrderEntityTransfer {
        $salesOrderEntityTransfer->setOmsProcessorIdentifier($this->omsProcessorIdGenerator->generateOmsProcessorIdentifier());

        return $salesOrderEntityTransfer;
    }
}
