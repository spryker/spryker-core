<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ApiKeyAuthorizationConnector\Business\Facade;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ApiKeyAuthorizationConnector
 * @group Business
 * @group Facade
 * @group Facade
 * @group ApiKeyAuthorizationConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ApiKeyAuthorizationConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAuthorizeReturnsTrueWhenIdentityIsCorrect(): void
    {
        //Arrange
        $authorizationRequestTransfer = $this->tester->getAuthorizationRequestTransferWithCorrectIdentity();

        //Act
        $authorizationResponseTransfer = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        //Assert
        $this->assertTrue($authorizationResponseTransfer->getIsAuthorized());
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsTrueWhenIdentityIsNotCorrect(): void
    {
        //Arrange
        $authorizationRequestTransfer = $this->tester->getAuthorizationRequestTransferWithIncorrectIdentity();

        //Act
        $authorizationResponseTransfer = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        //Assert
        $this->assertFalse($authorizationResponseTransfer->getIsAuthorized());
    }
}
