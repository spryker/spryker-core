<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\CodeBucket\Config;

use Spryker\Shared\Kernel\Store;

class DefaultCodeBucketConfig extends AbstractCodeBucketConfig
{
    /**
     * @var bool
     */
    protected $isDynamicStoreMode;

    /**
     * @param bool|null $isDynamicStoreMode
     */
    public function __construct(?bool $isDynamicStoreMode = null)
    {
        $this->isDynamicStoreMode = $this->resolveDynamicStoreMode($isDynamicStoreMode);
    }

    /**
     * @return string[]
     */
    public function getCodeBuckets(): array
    {
        if (!$this->isDynamicStoreMode) {
            return Store::getInstance()->getAllowedStores();
        }

        return [];
    }

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string
    {
        if (!$this->isDynamicStoreMode) {
            return Store::getInstance()->getStoreName();
        }

        return '';
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param bool|null $isDynamicStoreMode
     *
     * @return bool
     */
    protected function resolveDynamicStoreMode(?bool $isDynamicStoreMode = null): bool
    {
        return $isDynamicStoreMode ?? Store::isDynamicStoreMode();
    }
}
