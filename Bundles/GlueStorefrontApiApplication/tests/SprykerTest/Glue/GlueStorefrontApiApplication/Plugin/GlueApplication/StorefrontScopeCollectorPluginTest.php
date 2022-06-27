<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthScopeFindTransfer;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationDependencyProvider;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\Oauth\StorefrontScopeCollectorPlugin;
use Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeDefinitionPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Plugin
 * @group GlueApplication
 * @group StorefrontScopeCollectorPluginTest
 * Add your own group annotations below this line
 */
class StorefrontScopeCollectorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationTester
     */
    protected $tester;

    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\GlueStorefrontApiApplicationProviderPlugin::GLUE_STOREFRONT_API_APPLICATION_NAME
     *
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION_NAME = 'storefront';

    /**
     * @return void
     */
    public function testProvideScopesWillReturnEmptyArrayOfScopes(): void
    {
        // Arrange
        $storefrontScopeCollectorPlugin = new StorefrontScopeCollectorPlugin();

        //Act
        $scopes = $storefrontScopeCollectorPlugin->provideScopes();

        //Assert
        $this->assertEmpty($scopes);
    }

    /**
     * @return void
     */
    public function testProvideScopesWillReturnArrayOfOauthScopeFindTransferWithScopeData(): void
    {
        // Arrange
        $this->setDependency();
        $storefrontScopeCollectorPlugin = new StorefrontScopeCollectorPlugin();

        //Act
        $scopes = $storefrontScopeCollectorPlugin->provideScopes();

        //Assert
        $this->assertNotEmpty($scopes);
        $this->assertIsArray($scopes);
        $this->assertInstanceOf(OauthScopeFindTransfer::class, $scopes[0]);
        $this->assertSame(static::GLUE_STOREFRONT_API_APPLICATION_NAME, $scopes[0]->getApplicationName());
        $this->assertSame($this->tester::GET_METHOD_SCOPE, $scopes[0]->getIdentifier());
    }

    /**
     * @return void
     */
    protected function setDependency(): void
    {
        $this->tester->setDependency(
            GlueStorefrontApiApplicationDependencyProvider::PLUGINS_RESOURCE,
            [$this->createScopeDefinitionPluginMock()],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeDefinitionPluginInterface
     */
    protected function createScopeDefinitionPluginMock(): ScopeDefinitionPluginInterface
    {
        $scopes = [$this->tester::GET_METHOD_NAME => $this->tester::GET_METHOD_SCOPE];

        $scopeDefinitionPluginMockMock = $this->createMock(ScopeDefinitionPluginInterface::class);
        $scopeDefinitionPluginMockMock->expects($this->once())
            ->method('getScopes')
            ->willReturn($scopes);

        return $scopeDefinitionPluginMockMock;
    }
}
