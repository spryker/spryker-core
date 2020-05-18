<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Builder;

interface RestRequestValidatorCodeBucketCacheBuilderInterface
{
    /**
     * @param string $codeBucket
     *
     * @return void
     */
    public function buildCacheForCodeBucket(string $codeBucket): void;
}
