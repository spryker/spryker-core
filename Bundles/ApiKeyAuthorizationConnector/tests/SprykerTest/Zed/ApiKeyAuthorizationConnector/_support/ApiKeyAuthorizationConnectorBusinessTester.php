<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ApiKeyAuthorizationConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\AuthorizationEntityTransfer;
use Generated\Shared\Transfer\AuthorizationIdentityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Zed\ApiKeyAuthorizationConnector\Business\ApiKeyAuthorizationConnectorFacade;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Zed\ApiKeyAuthorizationConnector\PHPMD)
 */
class ApiKeyAuthorizationConnectorBusinessTester extends Actor
{
    use _generated\ApiKeyAuthorizationConnectorBusinessTesterActions;

    /**
     * @var string
     */
    protected const FOO_KEY = 'test';

    /**
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function getAuthorizationRequestTransferWithCorrectIdentity(): AuthorizationRequestTransfer
    {
        $identityTransfer = (new AuthorizationIdentityTransfer())
            ->setApiKeyIdentifier(static::FOO_KEY);

        return $this->createAuthorizationRequestTransfer($identityTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function getAuthorizationRequestTransferWithIncorrectIdentity(): AuthorizationRequestTransfer
    {
        $identityTransfer = (new AuthorizationIdentityTransfer())
            ->setApiKeyIdentifier(null);

        return $this->createAuthorizationRequestTransfer($identityTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function getAuthorizationRequestTransferWithoutIdentifier(): AuthorizationRequestTransfer
    {
        $identityTransfer = new AuthorizationIdentityTransfer();

        return $this->createAuthorizationRequestTransfer($identityTransfer);
    }

    /**
     * @return \SprykerTest\Zed\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorFacade
     */
    public function getFacade(): ApiKeyAuthorizationConnectorFacade
    {
        return new ApiKeyAuthorizationConnectorFacade();
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationIdentityTransfer $identityTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    protected function createAuthorizationRequestTransfer(
        AuthorizationIdentityTransfer $identityTransfer
    ): AuthorizationRequestTransfer {
        $entityTransfer = (new AuthorizationEntityTransfer())
            ->setData([
                'method' => 'GET',
                'path' => '/this/is/test',
            ]);

        return (new AuthorizationRequestTransfer())
            ->setIdentity($identityTransfer)
            ->setEntity($entityTransfer);
    }
}
