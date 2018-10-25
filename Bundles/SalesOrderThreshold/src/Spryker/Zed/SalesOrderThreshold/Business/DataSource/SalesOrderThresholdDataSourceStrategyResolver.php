<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\DataSource;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\SalesOrderThresholdReaderInterface;

class SalesOrderThresholdDataSourceStrategyResolver implements SalesOrderThresholdDataSourceStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface[]
     */
    protected $salesOrderThresholdDataSourceStrategyPlugins;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\SalesOrderThresholdReaderInterface
     */
    protected $salesOrderThresholdReader;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface[] $salesOrderThresholdDataSourceStrategyPlugins
     * @param \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\SalesOrderThresholdReaderInterface $salesOrderThresholdReader
     */
    public function __construct(
        array $salesOrderThresholdDataSourceStrategyPlugins,
        SalesOrderThresholdReaderInterface $salesOrderThresholdReader
    ) {
        $this->salesOrderThresholdDataSourceStrategyPlugins = $salesOrderThresholdDataSourceStrategyPlugins;
        $this->salesOrderThresholdReader = $salesOrderThresholdReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        $salesOrderThresholdTransfers = [];
        foreach ($this->salesOrderThresholdDataSourceStrategyPlugins as $salesOrderThresholdDataSourceStrategyPlugin) {
            $salesOrderThresholdTransfers = array_merge(
                $salesOrderThresholdTransfers,
                $salesOrderThresholdDataSourceStrategyPlugin->findApplicableThresholds($quoteTransfer)
            );
        }

        return $salesOrderThresholdTransfers;
    }
}
