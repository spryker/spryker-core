<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthUserConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthUserConnector
 * @group Business
 * @group Facade
 * @group OauthUserConnectorFacadeTest
 * Add your own group annotations below this line
 */
class OauthUserConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OauthUserConnector\OauthUserConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetOauthUserShouldReturnUserWhenCredentialsMatch(): void
    {
        // Arrange
        $oauthUserTransfer = new OauthUserTransfer();
        $oauthUserTransfer->setUsername('harald@spryker.com')
            ->setPassword('change123');

        // Act
        $oauthUserTransfer = $this->tester->getLocator()->oauthUserConnector()->facade()->getOauthUser($oauthUserTransfer);

        //Assert
        $this->assertTrue($oauthUserTransfer->getIsSuccess(), 'User must be authorized by valid credentials.');
    }

    /**
     * @return void
     */
    public function testGetOauthUserShouldReturnFailureUserWhenCredentialsNotMatch(): void
    {
        // Arrange
        $oauthUserTransfer = new OauthUserTransfer();
        $oauthUserTransfer->setUsername('harald@spryker.com')
            ->setPassword('change1233');

        // Act
        $oauthUserTransfer = $this->tester->getLocator()->oauthUserConnector()->facade()->getOauthUser($oauthUserTransfer);

        //Assert
        $this->assertFalse($oauthUserTransfer->getIsSuccess(), 'User must not be authorized by invalid credentials.');
    }

    /**
     * @return void
     */
    public function testGetScopesShouldReturnScopeListForRequest(): void
    {
        // Arrange
        $oauthScopeRequestTransfer = new OauthScopeRequestTransfer();

        // Act
        $scopes = $this->tester->getLocator()->oauthUserConnector()->facade()->getScopes($oauthScopeRequestTransfer);

        //Assert
        $this->assertNotEmpty($scopes, 'User scopes must be returned.');
    }
}
