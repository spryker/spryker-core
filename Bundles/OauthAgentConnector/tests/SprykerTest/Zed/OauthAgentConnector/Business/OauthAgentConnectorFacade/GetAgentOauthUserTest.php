<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthAgentConnector\Business\OauthAgentConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\UserBuilder;
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
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $userTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userTransfer = $this->tester->getLocator()->user()->facade()
            ->createUser((new UserBuilder(['password' => 'change123', 'isAgent' => true]))->build());
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tester->getLocator()->user()->facade()
            ->deactivateUser($this->userTransfer->getIdUser());

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testGetAgentOauthUserWillAuthorizeAnAgent(): void
    {
        // Arrange
        $oauthUserTransfer = (new OauthUserTransfer())
            ->setUsername($this->userTransfer->getUsername())
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
