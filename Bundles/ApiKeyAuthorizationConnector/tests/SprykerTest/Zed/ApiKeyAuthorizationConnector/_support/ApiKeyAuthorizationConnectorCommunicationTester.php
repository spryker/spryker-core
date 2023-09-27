<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ApiKeyAuthorizationConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\AuthorizationIdentityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;

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
class ApiKeyAuthorizationConnectorCommunicationTester extends Actor
{
    use _generated\ApiKeyAuthorizationConnectorCommunicationTesterActions;

    /**
     * @var string
     */
    protected const FOO_KEY = 'test';

    /**
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function getAuthorizationRequestTransferWithIdentity(): AuthorizationRequestTransfer
    {
        $identityTransfer = (new AuthorizationIdentityTransfer())
            ->setIdentifier(static::FOO_KEY);

        return (new AuthorizationRequestTransfer())
            ->setIdentity($identityTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function getAuthorizationRequestTransferWithoutIdentifier(): AuthorizationRequestTransfer
    {
        $identityTransfer = new AuthorizationIdentityTransfer();

        return (new AuthorizationRequestTransfer())
            ->setIdentity($identityTransfer);
    }
}
