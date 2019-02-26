<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch;

use Spryker\Shared\ProductReviewSearch\ProductReviewSearchConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductReviewSearchConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ProductReviewSearchConstants::SEARCH_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getProductReviewSynchronizationPoolName(): ?string
    {
        return null;
    }
}
