<?php

namespace SprykerTest\Shared\Kernel\CodeBucket;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContext;

class CodeBucketContextTest extends Unit
{
    public function testGetCurrentCodeBucketWithDynamicStoreModeAndWithoutCustomCodeBucketShouldReturnEmptyCodeBucket(): void
    {
        $codeBucketContextMock = $this->createCodeBucketContext();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContextInterface
     */
    public function createCodeBucketContext()
    {
        return $this->getMockBuilder(CodeBucketContext::class)->getMock();
    }
}
