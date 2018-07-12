<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReviewStorage;

use Spryker\Zed\ProductReviewStorage\ProductReviewStorageConfig;

class ProductReviewStorageConfigMock extends ProductReviewStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue()
    {
        return false;
    }
}
