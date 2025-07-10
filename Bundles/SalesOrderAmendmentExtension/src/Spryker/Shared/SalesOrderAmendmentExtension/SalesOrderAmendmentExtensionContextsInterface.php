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
     * - Enables workflows to distinguish order amendments from new orders.
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

    /**
     * Specification:
     * - Defines the Order Amendment Async Context, which applies when modifying an existing order.
     * - Enables workflows to distinguish order amendments async from new orders and order amendment.
     * - The main idea of the workflow is to run the most of the order amendment logic asynchronously via OMS after the order amendment is created.
     * - Enables plugins to execute context-specific logic.
     *
     * @api
     *
     * @var string
     */
    public const CONTEXT_ORDER_AMENDMENT_ASYNC = 'order-amendment-async';
}
