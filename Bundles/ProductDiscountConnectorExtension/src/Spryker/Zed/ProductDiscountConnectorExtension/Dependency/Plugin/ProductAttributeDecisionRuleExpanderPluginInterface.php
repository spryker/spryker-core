<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Use this plugin to expand product attribute discount decision rule.
 */
interface ProductAttributeDecisionRuleExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands decision rule with additional logic.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $currentItemTransfer,
        ClauseTransfer $clauseTransfer
    ): bool;
}
