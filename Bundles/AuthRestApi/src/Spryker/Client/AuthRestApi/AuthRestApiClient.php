<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthRestApi;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\CreateAccessTokenPreCheckResultTransfer;
use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\AuthRestApi\AuthRestApiFactory getFactory()
 */
class AuthRestApiClient extends AbstractClient implements AuthRestApiClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function createAccessToken(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        $oauthRequestTransfer->setAuthContext(
            (new AuthContextTransfer())
                ->setIp("192.168.0.1")
                ->setAccount($oauthRequestTransfer->getUsername())
        );

        $oauthRequestTransfer->requireAuthContext();

        $result = (new CreateAccessTokenPreCheckResultTransfer())
            ->setIsSuccess(true);

        foreach($this->getFactory()->getCreateAccessTokenPreCheckPlugins() as $plugin) {
            $result = $plugin->preCheck($oauthRequestTransfer, $result);
        }

        if ($result->getIsSuccess() === false) {
            return (new OauthResponseTransfer())
                ->setIsValid(false)
                ->setError(
                    (new OauthErrorTransfer())->setMessage('User is blocked')
                );
        }

        return $this->getFactory()->createAuthRestApiZedStub()->createAccessToken($oauthRequestTransfer);
    }
}
