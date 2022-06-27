<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Plugin\GlueApplication;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthScopeFindTransfer;
use Spryker\Glue\GlueBackendApiApplication\Plugin\Oauth\BackendScopeFinderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group Plugin
 * @group GlueApplication
 * @group BackendScopeFinderPluginTest
 * Add your own group annotations below this line
 */
class BackendScopeFinderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueBackendApiApplication\GlueBackendApiApplicationTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const GLUE_SCOPE_CACHE_PATH = '/GlueScopesCache/glue_scopes_cache.yml';

    /**
     * @var string
     */
    protected const NOT_EXIST_SCOPE_NAME = 'test:scope';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->mockConfigMethod(
            'getGeneratedFullFileNameForCollectedScopes',
            Configuration::dataDir() . static::GLUE_SCOPE_CACHE_PATH,
        );
    }

    /**
     * @return void
     */
    public function testFindScopeWillReturnNullBecauseInputScopeNotFound(): void
    {
        // Arrange
        $backendScopeFinderPlugin = new BackendScopeFinderPlugin();
        $backendScopeFinderPlugin->setFactory($this->tester->getFactory());
        $oauthScopeFindTransfer = (new OauthScopeFindTransfer())->setIdentifier(static::NOT_EXIST_SCOPE_NAME);

        //Act
        $scopeIdentifier = $backendScopeFinderPlugin->findScope($oauthScopeFindTransfer);

        //Assert
        $this->assertNull($scopeIdentifier);
    }

    /**
     * @return void
     */
    public function testFindScopeWillReturnInputScopeBecauseInputScopeFound(): void
    {
        // Arrange
        $backendScopeFinderPlugin = new BackendScopeFinderPlugin();
        $backendScopeFinderPlugin->setFactory($this->tester->getFactory());
        $oauthScopeFindTransfer = (new OauthScopeFindTransfer())->setIdentifier($this->tester::GET_METHOD_SCOPE);

        //Act
        $scopeIdentifier = $backendScopeFinderPlugin->findScope($oauthScopeFindTransfer);

        //Assert
        $this->assertSame($this->tester::GET_METHOD_SCOPE, $scopeIdentifier);
    }
}
