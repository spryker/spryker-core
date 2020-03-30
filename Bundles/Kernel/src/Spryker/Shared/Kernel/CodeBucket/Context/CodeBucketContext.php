<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\CodeBucket\Context;

use SprykerCodeBucketContext;

class CodeBucketContext implements CodeBucketContextInterface
{
    /**
     * @return string[]
     */
    public function getCodeBuckets(): array
    {
        return $this->resolveCodeBucket()->getCodeBuckets();
    }

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string
    {
        return $this->resolveCodeBucket()->getCurrentCodeBucket();
    }

    /**
     * @return \Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContextInterface
     */
    protected function resolveCodeBucket(): CodeBucketContextInterface
    {
        return class_exists(SprykerCodeBucketContext::class) ? new SprykerCodeBucketContext() : new DefaultCodeBucketContext();
    }
}
