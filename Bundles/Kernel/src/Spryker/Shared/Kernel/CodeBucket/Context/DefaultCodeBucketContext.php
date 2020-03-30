<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\CodeBucket\Context;

use Spryker\Shared\Kernel\Store;

class DefaultCodeBucketContext extends AbstractCodeBucketContext implements CodeBucketContextInterface
{
    /**
     * @return string[]
     */
    public function getCodeBuckets(): array
    {
        if (!Store::isDynamicStoreMode()) {
            return Store::getInstance()->getAllowedStores();
        }

        return [];
    }

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string
    {
        if (!Store::isDynamicStoreMode()) {
            return Store::getInstance()->getStoreName();
        }

        return '';
    }
}
