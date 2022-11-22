<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GlueBackendApiApplicationAuthorizationConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\AuthorizationEntityTransfer;
use Generated\Shared\Transfer\AuthorizationIdentityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @method \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\GlueBackendApiApplicationAuthorizationConnectorFacadeInterface getFacade()
 * @SuppressWarnings(PHPMD)
 */
class GlueBackendApiApplicationAuthorizationConnectorTester extends Actor
{
    use _generated\GlueBackendApiApplicationAuthorizationConnectorTesterActions;

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\AuthorizationIdentityTransfer|null $authorizationIdentityTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function createAuthorizationRequestTransfer(
        array $data,
        ?AuthorizationIdentityTransfer $authorizationIdentityTransfer = null
    ): AuthorizationRequestTransfer {
        return (new AuthorizationRequestTransfer())
            ->setIdentity($authorizationIdentityTransfer ?? new AuthorizationIdentityTransfer())
            ->setEntity(
                (new AuthorizationEntityTransfer())
                    ->setData($data),
            );
    }
}
