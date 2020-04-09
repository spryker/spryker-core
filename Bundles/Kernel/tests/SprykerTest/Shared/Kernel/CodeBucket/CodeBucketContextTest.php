<?php

namespace SprykerTest\Shared\Kernel\CodeBucket;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContext;
use Spryker\Shared\Kernel\Store;

class CodeBucketContextTest extends Unit
{
    public function testGetCurrentCodeBucketWithDynamicStoreModeAndWithoutCustomCodeBucketShouldReturnEmptyCodeBucket(): void
    {
        $codeBucketContextMock = $this->createCodeBucketContext();
        $reflectionStore = new \ReflectionClass(Store::class);
        $reflectionIsDynamicStoreMode = $reflectionStore->getProperty('isDynamicStoreMode');
        $reflectionIsDynamicStoreMode->setValue(true);

        $currentCodeBucket = $codeBucketContextMock->getCurrentCodeBucket();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContextInterface
     */
    public function createCodeBucketContext()
    {
        return $this->getMockBuilder(CodeBucketContext::class)->getMock();
    }
}
