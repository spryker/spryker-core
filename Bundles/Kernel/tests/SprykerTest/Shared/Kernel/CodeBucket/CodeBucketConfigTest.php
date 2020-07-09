<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\CodeBucket;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfig;
use Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface;
use Spryker\Shared\Kernel\CodeBucket\Config\DefaultCodeBucketConfig;
use Spryker\Shared\Kernel\CodeBucket\Exception\InvalidCodeBucketException;
use SprykerTest\Shared\Kernel\Fixtures\CodeBucketContext\TestCodeBucketConfigWithDefaultCodeBucket;
use SprykerTest\Shared\Kernel\Fixtures\CodeBucketContext\TestCodeBucketConfigWithInvalidDefaultCodeBucket;
use SprykerTest\Shared\Kernel\Fixtures\CodeBucketContext\TestCodeBucketConfigWithoutDefaultCodeBucket;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group CodeBucket
 * @group CodeBucketConfigTest
 * Add your own group annotations below this line
 */
class CodeBucketConfigTest extends Unit
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
        $codeBucketContext = $this->createCodeBucketConfig(new DefaultCodeBucketConfig(true));

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
        $codeBucketContext = $this->createCodeBucketConfig(new DefaultCodeBucketConfig(false));

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
        $codeBucketContext = $this->createCodeBucketConfig(new TestCodeBucketConfigWithoutDefaultCodeBucket());

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
        $codeBucketContext = $this->createCodeBucketConfig(new TestCodeBucketConfigWithDefaultCodeBucket());

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
        $codeBucketContext = $this->createCodeBucketConfig(new TestCodeBucketConfigWithInvalidDefaultCodeBucket());

        // Act
        $codeBucketContext->getCurrentCodeBucket();
    }

    /**
     * @param \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface|null $codeBucketConfig
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface
     */
    protected function createCodeBucketConfig(
        ?CodeBucketConfigInterface $codeBucketConfig = null
    ): CodeBucketConfigInterface {
        return new CodeBucketConfig($codeBucketConfig);
    }
}
