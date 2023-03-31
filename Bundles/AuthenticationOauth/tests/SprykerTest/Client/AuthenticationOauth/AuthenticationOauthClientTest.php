<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\AuthenticationOauth;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group AuthenticationOauth
 * @group AuthenticationOauthClientTest
 * Add your own group annotations below this line
 */
class AuthenticationOauthClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\AuthenticationOauth\AuthenticationOauthClientTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const TEST_USERNAME = 'sonia@spryker.com';

    /**
     * @var string
     */
    protected const TEST_USERNAME_INVALID = 'harald@spryker.com';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testAuthenticateWithValidCredentialsIsSuccessful(): void
    {
        //Arrange
        $authenticationOauthClient = $this->tester->getLocator()->authenticationOauth()->client();
        $glueAuthenticationRequestTransfer = $this->tester->haveGlueAuthenticationRequestTransfer(static::TEST_USERNAME);

        //Act
        $glueAuthenticationResponseTransfer = $authenticationOauthClient->authenticate($glueAuthenticationRequestTransfer);

        //Assert
        $this->assertTrue($glueAuthenticationResponseTransfer->getOauthResponse()->getIsValid());
        $this->assertNotEmpty($glueAuthenticationResponseTransfer->getOauthResponse()->getAccessToken());
    }

    /**
     * @return void
     */
    public function testAuthenticateWithInvalidCredentialsIsFailed(): void
    {
        //Arrange
        $authenticationOauthClient = $this->tester->getLocator()->authenticationOauth()->client();
        $glueAuthenticationRequestTransfer = $this->tester->haveGlueAuthenticationRequestTransfer(static::TEST_USERNAME_INVALID);

        //Act
        $glueAuthenticationResponseTransfer = $authenticationOauthClient->authenticate($glueAuthenticationRequestTransfer);

        //Assert
        $this->assertFalse($glueAuthenticationResponseTransfer->getOauthResponse()->getIsValid());
        $this->assertEmpty($glueAuthenticationResponseTransfer->getOauthResponse()->getAccessToken());
    }
}
