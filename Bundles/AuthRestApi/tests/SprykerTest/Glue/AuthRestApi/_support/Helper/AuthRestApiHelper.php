<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * - Returns OauthResponseTransfer with error if authorization failed.
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
        ?string $anonymousCustomerReference = null
    ): OauthResponseTransfer {
        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->setGrantType(AuthRestApiConfig::CLIENT_GRANT_PASSWORD)
            ->setUsername($customerTransfer->getEmail())
            ->setPassword($customerTransfer->getNewPassword());

        if ($anonymousCustomerReference) {
            $oauthRequestTransfer->setCustomerReference($anonymousCustomerReference);
        }

        return $this->getLocator()
            ->authRestApi()
            ->facade()
            ->createAccessToken($oauthRequestTransfer);
    }
}
