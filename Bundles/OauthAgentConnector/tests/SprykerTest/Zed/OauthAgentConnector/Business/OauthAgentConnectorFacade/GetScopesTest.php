<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthAgentConnector\Business\OauthAgentConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthAgentConnector
 * @group Business
 * @group OauthAgentConnectorFacade
 * @group GetScopesTest
 * Add your own group annotations below this line
 */
class GetScopesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OauthAgentConnector\OauthAgentConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetScopesWillReturnScopes(): void
    {
        // Arrange
        $scopesRequestTransfer = new OauthScopeRequestTransfer();

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getScopes($scopesRequestTransfer);

        // Assert
        $this->assertNotEmpty($oauthScopeTransfers);
    }

    /**
     * @return void
     */
    public function testGetScopesWillReturnScopesWithDefault(): void
    {
        // Arrange
        $defaultScopeTransfer = (new OauthScopeTransfer())->setIdentifier('test');
        $scopesRequestTransfer = (new OauthScopeRequestTransfer())
            ->addScope($defaultScopeTransfer);

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getScopes($scopesRequestTransfer);

        // Assert
        $this->assertNotEmpty($oauthScopeTransfers);
        $this->assertContains($defaultScopeTransfer, $oauthScopeTransfers);
    }
}
