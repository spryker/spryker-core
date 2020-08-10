<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthAgentConnector\Business\OauthAgentConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthUserTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthAgentConnector
 * @group Business
 * @group OauthAgentConnectorFacade
 * @group GetAgentOauthUserTest
 * Add your own group annotations below this line
 */
class GetAgentOauthUserTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OauthAgentConnector\OauthAgentConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetAgentOauthUserWillAuthorizeAnAgent(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveRegisteredAgent(['password' => 'change123']);
        $oauthUserTransfer = (new OauthUserTransfer())
            ->setUsername($userTransfer->getUsername())
            ->setPassword('change123');

        // Act
        $resultingOauthUserTransfer = $this->tester->getFacade()->getAgentOauthUser($oauthUserTransfer);

        // Assert
        $this->assertTrue($resultingOauthUserTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testGetAgentOauthUserWillNotAuthorizeAnAgentWithWrongCredentials(): void
    {
        // Arrange
        $oauthUserTransfer = (new OauthUserTransfer())
            ->setUsername('admin@spryker.com')
            ->setPassword('change1233');

        // Act
        $resultingOauthUserTransfer = $this->tester->getFacade()->getAgentOauthUser($oauthUserTransfer);

        // Assert
        $this->assertFalse($resultingOauthUserTransfer->getIsSuccess());
    }
}
