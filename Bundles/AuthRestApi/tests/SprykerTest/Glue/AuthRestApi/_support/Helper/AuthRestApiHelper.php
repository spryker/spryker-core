<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\AuthRestApi\Helper;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerTest\Glue\Testify\Helper\GlueRest;

class AuthRestApiHelper extends GlueRest
{
    protected const RESOURCE_ACCESS_TOKENS = 'access-tokens';

    /**
     * Publishes access token
     *
     * @part json
     *
     * @param string $token
     *
     * @return void
     */
    public function amAuthorizedGlueUser(string $token): void
    {
        $this->amBearerAuthenticated($token);
    }

    /**
     * @part json
     *
     * @param string $anonymousCustomerReference
     *
     * @return void
     */
    public function amUnauthorizedGlueUser(string $anonymousCustomerReference): void
    {
        $this->haveHttpHeader('X-Anonymous-Customer-Unique-Id', $anonymousCustomerReference);
    }

    /**
     * @part json
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array|null
     */
    public function haveAuthorizationToGlue(CustomerTransfer $customerTransfer): ?array
    {
        $this->sendPOST(static::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => static::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $customerTransfer->getEmail(),
                    'password' => $customerTransfer->getNewPassword() ?: static::DEFAULT_PASSWORD,
                ],
            ],
        ]);

        return $this->grabDataFromResponseByJsonPath('$.data.attributes')[0] ?: [];
    }
}
