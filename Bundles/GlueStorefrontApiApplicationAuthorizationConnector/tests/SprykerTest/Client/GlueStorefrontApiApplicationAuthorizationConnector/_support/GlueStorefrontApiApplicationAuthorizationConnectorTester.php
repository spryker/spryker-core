<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\GlueStorefrontApiApplicationAuthorizationConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\AuthorizationEntityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorFactory;

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
 * @SuppressWarnings(PHPMD)
 */
class GlueStorefrontApiApplicationAuthorizationConnectorTester extends Actor
{
    use _generated\GlueStorefrontApiApplicationAuthorizationConnectorTesterActions;

    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function createAuthorizationRequestTransfer(array $data): AuthorizationRequestTransfer
    {
        return (new AuthorizationRequestTransfer())
            ->setEntity(
                (new AuthorizationEntityTransfer())
                    ->setData($data),
            );
    }

    /**
     * @return \Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorFactory
     */
    public function createGlueStorefrontApiApplicationAuthorizationConnectorFactory(): GlueStorefrontApiApplicationAuthorizationConnectorFactory
    {
        return new GlueStorefrontApiApplicationAuthorizationConnectorFactory();
    }
}
