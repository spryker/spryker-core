<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductReviewSearch;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ProductReviewSearchConstants
{
    /**
     * Specification:
     * - Enables/disables search synchronization.
     *
     * @api
     *
     * @uses \Spryker\Shared\Synchronization\SynchronizationConstants::SEARCH_SYNC_ENABLED
     */
    public const SEARCH_SYNC_ENABLED = 'SYNCHRONIZATION:SEARCH_SYNC_ENABLED';
}
