<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\CodeBucket\Config;

interface CodeBucketConfigInterface
{
    /**
     * @return array<string>
     */
    public function getCodeBuckets(): array;

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string;
}
