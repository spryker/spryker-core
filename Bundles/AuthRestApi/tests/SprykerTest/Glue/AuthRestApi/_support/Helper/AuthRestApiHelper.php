<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\AuthRestApi\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AuthRestApiHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * Specification:
     * - Authorizes customer and returns OauthResponseTransfer.
     * - Fails test in case oauth request was not successful.
     *
     * @part json
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string|null $anonymousCustomerReference
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function haveAuthorizationToGlue(
        CustomerTransfer $customerTransfer,
        ?string $anonymousCustomerReference = null,
        ?string $clientId = null,
        ?string $clientSecret = null
    ): OauthResponseTransfer {
        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->setGrantType(AuthRestApiConfig::CLIENT_GRANT_PASSWORD)
            ->setUsername($customerTransfer->getEmail())
            ->setPassword($customerTransfer->getNewPassword())
            ->setClientId($clientId)
            ->setClientSecret($clientSecret);

        if ($anonymousCustomerReference) {
            $oauthRequestTransfer->setCustomerReference($anonymousCustomerReference);
        }

        $oauthResponseTransfer = $this->getLocator()
            ->authRestApi()
            ->facade()
            ->createAccessToken($oauthRequestTransfer);

        $this->assertTrue($oauthResponseTransfer->getIsValid(), 'OAuth token request failed');

        return $oauthResponseTransfer;
    }
}
