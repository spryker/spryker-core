<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\CodeBucket;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContext;
use Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContextInterface;
use Spryker\Shared\Kernel\CodeBucket\Context\DefaultCodeBucketContext;
use Spryker\Shared\Kernel\CodeBucket\Exception\InvalidCodeBucketException;
use SprykerTest\Shared\Kernel\Fixtures\CodeBucketContext\TestCodeBucketContextWithDefaultCodeBucket;
use SprykerTest\Shared\Kernel\Fixtures\CodeBucketContext\TestCodeBucketContextWithInvalidDefaultCodeBucket;
use SprykerTest\Shared\Kernel\Fixtures\CodeBucketContext\TestCodeBucketContextWithoutDefaultCodeBucket;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group CodeBucket
 * @group CodeBucketContextTest
 * Add your own group annotations below this line
 */
class CodeBucketContextTest extends Unit
{
    /**
     * @var \SprykerTest\Shared\Kernel\KernelSharedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCurrentCodeBucketWithDynamicStoreModeAndWithoutCustomCodeBucketShouldReturnEmptyCodeBucket(): void
    {
        // Arrange
        $codeBucketContext = $this->createCodeBucketContext(new DefaultCodeBucketContext(true));

        // Act
        $currentCodeBucket = $codeBucketContext->getCurrentCodeBucket();

        // Assert
        $this->assertEmpty($currentCodeBucket, 'CodeBucket should be empty');
    }

    /**
     * @return void
     */
    public function testGetCurrentCodeBucketWithoutDynamicStoreModeAndWithoutCustomCodeBucketShouldReturnCurrentStore(): void
    {
        // Arrange
        $codeBucketContext = $this->createCodeBucketContext(new DefaultCodeBucketContext(false));

        // Act
        $currentCodeBucket = $codeBucketContext->getCurrentCodeBucket();

        // Assert
        $this->assertSame($currentCodeBucket, APPLICATION_STORE);
    }

    /**
     * @return void
     */
    public function testGetCurrentCodeBucketWithDynamicStoreModeAndWithCustomCodeBucketEmptyCodeBucketMustUsedShouldReturnEmptyCodeBucket(): void
    {
        // Arrange
        $codeBucketContext = $this->createCodeBucketContext(new TestCodeBucketContextWithoutDefaultCodeBucket());

        // Act
        $currentCodeBucket = $codeBucketContext->getCurrentCodeBucket();

        // Assert
        $this->assertEmpty($currentCodeBucket, 'CodeBucket should be empty');
    }

    /**
     * @return void
     */
    public function testGetCurrentCodeBucketWithCustomCodeBucketShouldReturnCustomCodeBucket(): void
    {
        // Arrange
        $codeBucketContext = $this->createCodeBucketContext(new TestCodeBucketContextWithDefaultCodeBucket());

        // Act
        $currentCodeBucket = $codeBucketContext->getCurrentCodeBucket();

        // Assert
        $this->assertSame(
            $currentCodeBucket,
            'test2',
            'Current code bucket should be equal to expected one'
        );
    }

    /**
     * @return void
     */
    public function testGetCurrentCodeBucketWithCustomInvalidCodeBucketShouldThrowException(): void
    {
        // Assert
        $this->expectException(InvalidCodeBucketException::class);

        // Arrange
        $this->tester->haveCodeBucketEnv('test4');
        $codeBucketContext = $this->createCodeBucketContext(new TestCodeBucketContextWithInvalidDefaultCodeBucket());

        // Act
        $codeBucketContext->getCurrentCodeBucket();
    }

    /**
     * @param \Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContextInterface|null $codeBucketContext
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContextInterface
     */
    protected function createCodeBucketContext(
        ?CodeBucketContextInterface $codeBucketContext = null
    ): CodeBucketContextInterface {
        return new CodeBucketContext($codeBucketContext);
    }
}
