<?php


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
     * @return \Spryker\Shared\Kernel\CodeBucket\Context\DefaultCodeBucketContext|\Spryker\Shared\Kernel\CodeBucket\Context\SprykerCodeBucketContext
     */
    protected function resolveCodeBucket()
    {
        return class_exists(SprykerCodeBucketContext::class) ? new SprykerCodeBucketContext() : new DefaultCodeBucketContext();
    }
}
