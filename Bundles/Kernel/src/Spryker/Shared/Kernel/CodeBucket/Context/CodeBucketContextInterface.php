<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\CodeBucket\Context;

interface CodeBucketContextInterface
{
    /**
     * @return string[]
     */
    public function getCodeBuckets(): array;

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string;
}
