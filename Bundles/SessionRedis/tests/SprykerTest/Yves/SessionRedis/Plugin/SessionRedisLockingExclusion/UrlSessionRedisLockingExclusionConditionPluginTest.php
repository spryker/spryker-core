<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Unit\Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion\UrlSessionRedisLockingExclusionConditionPlugin;
use Spryker\Yves\SessionRedis\SessionRedisConfig;
use Spryker\Yves\SessionRedis\SessionRedisDependencyProvider;
use SprykerTest\Yves\SessionRedis\SessionRedisYvesTester;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group SessionRedis
 * @group Plugin
 * @group SessionRedisLockingExclusion
 * @group UrlSessionRedisLockingExclusionConditionPluginTest
 * Add your own group annotations below this line
 */
class UrlSessionRedisLockingExclusionConditionPluginTest extends Unit
{
    /**
     * @var \Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion\UrlSessionRedisLockingExclusionConditionPlugin
     */
    protected UrlSessionRedisLockingExclusionConditionPlugin $plugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\SessionRedis\SessionRedisConfig
     */
    protected MockObject|SessionRedisConfig $configMock;

    /**
     * @var \SprykerTest\Yves\SessionRedis\SessionRedisYvesTester
     */
    protected SessionRedisYvesTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(SessionRedisDependencyProvider::REQUEST_STACK, new RequestStack());
        $this->configMock = $this->getMockBuilder(SessionRedisConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory = $this->tester->getFactory();
        $factory->setConfig($this->configMock);

        $this->plugin = new UrlSessionRedisLockingExclusionConditionPlugin();
        $this->plugin->setFactory($factory);
    }

    /**
     * @dataProvider excludedUrlPatternsDataProvider
     *
     * @param array $excludedUrlPatterns
     * @param string $requestUri
     *
     * @return void
     */
    public function testCheckConditionReturnsTrueForExcludedUrls(
        array $excludedUrlPatterns,
        string $requestUri
    ): void {
        // Arrange
        $this->configMock
            ->method('getSessionRedisLockingExcludedUrlPatterns')
            ->willReturn($excludedUrlPatterns);

        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();
        $redisLockingSessionHandlerConditionTransfer->setRequestUri($requestUri);

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertTrue($result, sprintf(
            'Failed asserting that request URI "%s" matches one of the excluded patterns',
            $requestUri,
        ));
    }

    /**
     * @dataProvider nonExcludedUrlPatternsDataProvider
     *
     * @param array $excludedUrlPatterns
     * @param string $requestUri
     *
     * @return void
     */
    public function testCheckConditionReturnsFalseForNonExcludedUrls(
        array $excludedUrlPatterns,
        string $requestUri
    ): void {
        // Arrange
        $this->configMock
            ->method('getSessionRedisLockingExcludedUrlPatterns')
            ->willReturn($excludedUrlPatterns);

        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();
        $redisLockingSessionHandlerConditionTransfer->setRequestUri($requestUri);

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result, sprintf(
            'Failed asserting that request URI "%s" does not match any of the excluded patterns',
            $requestUri,
        ));
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsFalseWhenRequestUriIsEmpty(): void
    {
        // Arrange
        $this->configMock
            ->method('getSessionRedisLockingExcludedUrlPatterns')
            ->willReturn(['/api/*', '/health-check']);

        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();
        $redisLockingSessionHandlerConditionTransfer->setRequestUri('');

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsFalseWhenRequestUriIsNotProvided(): void
    {
        // Arrange
        $this->configMock
            ->method('getSessionRedisLockingExcludedUrlPatterns')
            ->willReturn(['/api/*', '/health-check']);

        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsFalseWhenNoUrlPatternsAreConfigured(): void
    {
        // Arrange
        $this->configMock
            ->method('getSessionRedisLockingExcludedUrlPatterns')
            ->willReturn([]);

        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();
        $redisLockingSessionHandlerConditionTransfer->setRequestUri('/api/v1/products');

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return array<string, array>
     */
    public function excludedUrlPatternsDataProvider(): array
    {
        return [
            'exact match' => [
                ['#^/health-check$#', '#^/api/.*$#'],
                '/health-check',
            ],
            'pattern match with wildcard' => [
                ['#^/api/.*$#'],
                '/api/v1/products',
            ],
            'multiple patterns with match' => [
                ['#^/health-check$#', '#^/api/.*$#', '#^/status/.*$#'],
                '/status/server',
            ],
            'pattern with special characters' => [
                ['#^/api/\d+/.*$#'],
                '/api/123/users',
            ],
            'case sensitive match' => [
                ['#^/API/.*$#'],
                '/API/v1/products',
            ],
            'multiple wildcards' => [
                ['#^/api/.*/users/.*/profile$#'],
                '/api/v1/users/123/profile',
            ],
            'query string handling' => [
                ['#^/search.*$#'],
                '/search?q=test&page=1',
            ],
        ];
    }

    /**
     * @return array<string, array>
     */
    public function nonExcludedUrlPatternsDataProvider(): array
    {
        return [
            'no pattern match' => [
                ['#^/api/.*$#', '#^/health-check$#'],
                '/products',
            ],
            'partial pattern match' => [
                ['#^/api/v1/.*$#'],
                '/not-api/v1/products',
            ],
            'case mismatch' => [
                ['#^/API/.*$#'],
                '/api/v1/products',
            ],
            'similar but non-matching path' => [
                ['#^/health-check$#'],
                '/health-checker',
            ],
            'empty patterns with path' => [
                [],
                '/api/v1/products',
            ],
            'pattern with specific version mismatch' => [
                ['#^/api/v1/.*$#'],
                '/api/v2/products',
            ],
            'incorrect wildcard usage' => [
                ['#^/api/\d+/users$#'],
                '/api/abc/users',
            ],
        ];
    }
}
