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
     * @var string
     */
    protected const USER_PASSWORD_VALUE = 'change123';

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

        $userSeed = [
            'password' => static::USER_PASSWORD_VALUE,
            'isAgent' => true,
        ];

        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = (new UserBuilder($userSeed))
            ->build();

        $this->userTransfer = $this->tester
            ->getUserFacade()
            ->createUser($userTransfer);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tester
            ->getUserFacade()
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
            ->setPassword(static::USER_PASSWORD_VALUE);

        // Act
        $resultingOauthUserTransfer = $this->tester->getFacade()->getAgentOauthUser($oauthUserTransfer);

        // Assert
        $this->assertTrue($resultingOauthUserTransfer->getIsSuccess(), 'Agent user should be authorized with valid credentials.');
    }

    /**
     * @return void
     */
    public function testGetAgentOauthUserWillNotAuthorizeAnAgentWithWrongCredentials(): void
    {
        // Arrange
        $oauthUserTransfer = (new OauthUserTransfer())
            ->setUsername($this->userTransfer->getUsername())
            ->setPassword('change1233');

        // Act
        $resultingOauthUserTransfer = $this->tester->getFacade()->getAgentOauthUser($oauthUserTransfer);

        // Assert
        $this->assertFalse($resultingOauthUserTransfer->getIsSuccess(), 'Agent should not be able to authorize with wrong credentials.');
    }

    /**
     * @return void
     */
    public function testGetAgentOauthUserWillNotAuthorizeAnAgentWithInactiveStatus(): void
    {
        // Arrange
        $this->tester->getUserFacade()->deactivateUser($this->userTransfer->getIdUser());

        $oauthUserTransfer = (new OauthUserTransfer())
            ->setUsername($this->userTransfer->getUsername())
            ->setPassword(static::USER_PASSWORD_VALUE);

        // Act
        $resultingOauthUserTransfer = $this->tester->getFacade()->getAgentOauthUser($oauthUserTransfer);

        // Assert
        $this->assertFalse($resultingOauthUserTransfer->getIsSuccess(), 'Agent should not be able to authorize with inactive account.');
    }
}
