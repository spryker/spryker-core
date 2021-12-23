<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface;

class QuoteExpander implements QuoteExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface
     */
    protected $salesOrderThresholdDataSourceStrategyResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Expander\SalesOrderThresholdValueExpanderInterface
     */
    protected $salesOrderThresholdValueExpander;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface $salesOrderThresholdDataSourceStrategyResolver
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Expander\SalesOrderThresholdValueExpanderInterface $salesOrderThresholdValueExpander
     */
    public function __construct(
        SalesOrderThresholdDataSourceStrategyResolverInterface $salesOrderThresholdDataSourceStrategyResolver,
        SalesOrderThresholdValueExpanderInterface $salesOrderThresholdValueExpander
    ) {
        $this->salesOrderThresholdDataSourceStrategyResolver = $salesOrderThresholdDataSourceStrategyResolver;
        $this->salesOrderThresholdValueExpander = $salesOrderThresholdValueExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithSalesOrderThresholdValues(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getTotals()) {
            return $quoteTransfer;
        }

        $salesOrderThresholdValueTransfers = $this->salesOrderThresholdDataSourceStrategyResolver
            ->findApplicableThresholds($quoteTransfer);

        if (!$salesOrderThresholdValueTransfers) {
            return $quoteTransfer;
        }

        $salesOrderThresholdValueTransfers = $this->salesOrderThresholdValueExpander->expandSalesOrderThresholdValues(
            $salesOrderThresholdValueTransfers,
            $quoteTransfer,
        );

        return $quoteTransfer->setSalesOrderThresholdValues(new ArrayObject($salesOrderThresholdValueTransfers));
    }
}
