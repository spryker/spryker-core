<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AnalyticsGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AnalyticsCollectionTransfer;
use Generated\Shared\Transfer\AnalyticsRequestTransfer;

/**
 * Implement this plugin interface to add new analytics to analytics page.
 */
interface AnalyticsCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the analytics collection with additional analytics.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AnalyticsRequestTransfer $analyticsRequestTransfer
     * @param \Generated\Shared\Transfer\AnalyticsCollectionTransfer $analyticsCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\AnalyticsCollectionTransfer
     */
    public function expand(
        AnalyticsRequestTransfer $analyticsRequestTransfer,
        AnalyticsCollectionTransfer $analyticsCollectionTransfer
    ): AnalyticsCollectionTransfer;
}
