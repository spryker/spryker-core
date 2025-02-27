<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SalesOrderAmendmentExtension;

interface SalesOrderAmendmentExtensionContextsInterface
{
    /**
     * Specification:
     * - Defines the Order Amendment Context, which applies when modifying an existing order.
     * - Enables workflows to distingiush order amendmetns from new orders.
     * - The order already exists and is being modified, not newly created.
     * - Checkout steps may be altered, bypassed, or replaced.
     * - Processing logic adapts to amendment (eg item changes, price adjustments).
     * - Enables plugins to execute context-specific logic.
     *
     * @api
     *
     * @var string
     */
    public const CONTEXT_ORDER_AMENDMENT = 'order-amendment';
}
