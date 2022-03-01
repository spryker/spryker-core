<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request\Data;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\Exception\UserAlreadySetException;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;

/**
 * @deprecated Will be removed without replacement.
 *
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Request
 * @group Data
 * @group RestRequestTest
 *
 * Add your own group annotations below this line
 */
class RestRequestTest extends Unit
{
    /**
     * @return void
     */
    protected function _before()
    {
        error_reporting(error_reporting() & ~E_USER_DEPRECATED);
    }

    /**
     * @return void
     */
    protected function _after()
    {
        error_reporting(error_reporting() & E_USER_DEPRECATED);
    }

    /**
     * @return void
     */
    public function testSetUserForUserException(): void
    {
        // Arrange
        $restRequest = (new RestRequest())->createRestRequest();
        $restRequest->setUser('10', 'test', []);

        // Assert
        $this->expectException(UserAlreadySetException::class);

        // Act
        $restRequest->setUser('10', 'test', []);
    }

    /**
     * @return void
     */
    public function testSetUserForRestUserException(): void
    {
        // Arrange
        $restRequest = (new RestRequest())->createRestRequest();
        $restRequest->setRestUser((new RestUserTransfer()));

        // Assert
        $this->expectException(UserAlreadySetException::class);

        // Act
        $restRequest->setUser('10', 'test', []);
    }

    /**
     * @return void
     */
    public function testSetRestUserException(): void
    {
        // Arrange
        $restRequest = (new RestRequest())->createRestRequest();
        $restRequest->setRestUser((new RestUserTransfer()));

        // Assert
        $this->expectException(UserAlreadySetException::class);

        // Act
        $restRequest->setRestUser((new RestUserTransfer()));
    }

    /**
     * @return void
     */
    public function testGetRestUserWithSetUser(): void
    {
        // Arrange
        $restRequest = (new RestRequest())->createRestRequest();
        $restRequest->setUser('10', 'naturalIdentifier', []);

        // Act
        $restUserTransfer = $restRequest->getRestUser();

        // Assert
        $this->assertSame('naturalIdentifier', $restUserTransfer->getNaturalIdentifier());
        $this->assertSame(10, $restUserTransfer->getSurrogateIdentifier());
    }

    /**
     * @return void
     */
    public function testRestUserAndUserDataIsTheSame(): void
    {
        // Arrange
        $restUserTransfer = new RestUserTransfer();
        $restUserTransfer->setScopes([]);
        $restUserTransfer->setNaturalIdentifier('naturalIdentifier');
        $restUserTransfer->setSurrogateIdentifier(10);
        $restRequest = (new RestRequest())->createRestRequest();
        $restRequest->setRestUser($restUserTransfer);

        // Act
        $user = $restRequest->getUser();

        // Assert
        $this->assertSame($user->getNaturalIdentifier(), $restUserTransfer->getNaturalIdentifier());
        $this->assertSame($user->getSurrogateIdentifier(), (string)$restUserTransfer->getSurrogateIdentifier());
    }
}
