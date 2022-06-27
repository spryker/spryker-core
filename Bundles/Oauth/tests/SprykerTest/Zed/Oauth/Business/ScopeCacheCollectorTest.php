<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oauth\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthScopeFindTransfer;
use Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeCollectorPluginInterface;
use Spryker\Zed\Oauth\OauthDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oauth
 * @group Business
 * @group ScopeCacheCollectorTest
 * Add your own group annotations below this line
 */
class ScopeCacheCollectorTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_FILE_NAME = 'glue_scopes_cache_test.yml';

    /**
     * @var string
     */
    protected const TEST_APPLICATION_NAME = 'test';

    /**
     * @var string
     */
    protected $testOutputFilePath;

    /**
     * @var \SprykerTest\Zed\Oauth\OauthBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->testOutputFilePath = codecept_output_dir() . static::TEST_FILE_NAME;
        $this->setDependency();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        if (file_exists($this->testOutputFilePath)) {
            unlink($this->testOutputFilePath);
        }
    }

    /**
     * @return void
     */
    public function testCreateScopesCacheFileAndCheckIfExist(): void
    {
        //Arrange
        $this->tester->mockConfigMethod('getGeneratedFullFileNameForCollectedScopes', $this->testOutputFilePath);

        //Act
        $this->tester->getFactory()->createScopeCacheCollector()->collect();

        //Assert
        $this->assertFileExists($this->testOutputFilePath);
    }

    /**
     * @return void
     */
    protected function setDependency(): void
    {
        $this->tester->setDependency(
            OauthDependencyProvider::PLUGINS_SCOPE_COLLECTOR,
            [$this->createScopeCollectorPluginMock()],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeCollectorPluginInterface
     */
    protected function createScopeCollectorPluginMock(): ScopeCollectorPluginInterface
    {
        $oauthScopeFindTransfer = new OauthScopeFindTransfer();
        $oauthScopeFindTransfer->setApplicationName(static::TEST_APPLICATION_NAME);
        $oauthScopeFindTransfer->setIdentifier($this->tester::GET_METHOD_SCOPE);
        $scopes = [$oauthScopeFindTransfer];

        $scopeDefinitionPluginMockMock = $this->createMock(ScopeCollectorPluginInterface::class);
        $scopeDefinitionPluginMockMock->expects($this->once())
            ->method('provideScopes')
            ->willReturn($scopes);

        return $scopeDefinitionPluginMockMock;
    }
}
