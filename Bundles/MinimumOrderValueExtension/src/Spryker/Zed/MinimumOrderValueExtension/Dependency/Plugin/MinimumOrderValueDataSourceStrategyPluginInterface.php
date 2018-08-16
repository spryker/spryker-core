<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface MinimumOrderValueDataSourceStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if a threshold is applicable for the given Quote.
     * - Return the applicable threshold data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array;
}
