<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\QuoteExpander;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface;

class QuoteExpander implements QuoteExpanderInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface
     */
    protected $minimumOrderValueDataSourceStrategy;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\DataSource\ThresholdDataSourceStrategyInterface $minimumOrderValueDataSourceStrategy
     */
    public function __construct(ThresholdDataSourceStrategyInterface $minimumOrderValueDataSourceStrategy)
    {
        $this->minimumOrderValueDataSourceStrategy = $minimumOrderValueDataSourceStrategy;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addMinimumOrderValueThresholdsToQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->refreshValuesInQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function refreshValuesInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $minimumOrderValueThresholdTransfers = $this->findApplicableMinimumOrderValueThresholds($quoteTransfer);

        if (empty($minimumOrderValueThresholdTransfers)) {
            return $quoteTransfer;
        }

        $quoteTransfer->setMinimumOrderValueThresholds(
            (new ArrayObject($minimumOrderValueThresholdTransfers))
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer[]
     */
    protected function findApplicableMinimumOrderValueThresholds(QuoteTransfer $quoteTransfer): array
    {
        return $this->minimumOrderValueDataSourceStrategy->findApplicableThresholds($quoteTransfer);
    }
}
