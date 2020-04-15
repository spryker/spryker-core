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
     * @var \Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContextInterface
     */
    protected $customCodeBucketContext;

    /**
     * @param \Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContextInterface|null $customCodeBucketContext
     */
    public function __construct(?CodeBucketContextInterface $customCodeBucketContext = null)
    {
        $this->customCodeBucketContext = $customCodeBucketContext ?? new SprykerCodeBucketContext();
    }

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
        return $this->customCodeBucketContext;
    }
}
