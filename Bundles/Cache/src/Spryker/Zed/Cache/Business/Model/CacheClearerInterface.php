<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business\Model;

interface CacheClearerInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\Cache\Business\Model\CacheClearerInterface::clearCodeBucketCache()} instead.
     *
     * @return array<string>
     */
    public function clearCache();

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<string>
     */
    public function clearAutoLoaderCache();

    /**
     * @return string
     */
    public function clearCodeBucketCache(): string;

    /**
     * @return string
     */
    public function clearDefaultCodeBucketCache(): string;

    /**
     * @return array<string>
     */
    public function clearProjectSpecificCache(): array;
}
