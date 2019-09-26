<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Oauth\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class OauthHelper extends Module
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
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function haveAuth(CustomerTransfer $customerTransfer): OauthResponseTransfer
    {
        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->setGrantType(AuthRestApiConfig::CLIENT_GRANT_PASSWORD)
            ->setUsername($customerTransfer->getEmail())
            ->setPassword($customerTransfer->getNewPassword());

        return $this->getLocator()
            ->oauth()
            ->facade()
            ->processAccessTokenRequest($oauthRequestTransfer);
    }
}
