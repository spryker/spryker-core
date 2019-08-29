<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\AuthRestApi\Helper;

use Codeception\Module\REST;
use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CustomerTransfer;

class AuthRestApiHelper extends REST
{
    public const DEFAULT_PASSWORD = 'Pass$.123456';

    protected const RESOURCE_ACCESS_TOKENS = 'access-tokens';

    /**
     * Publishes access token
     *
     * @part json
     *
     * @param string $token
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amAuthorizedGlueUser(string $token, CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->amBearerAuthenticated($token);

        return $customerTransfer;
    }

    /**
     * Publishes access token
     *
     * @part json
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amUnauthorizedGlueUser(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->haveHttpHeader('X-Anonymous-Customer-Unique-Id', $customerTransfer->getCustomerReference());

        return $customerTransfer;
    }

    /**
     * @part json
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array ["tokenType" => string, "expiresIn" => int, "accessToken" => string, "refreshToken" => string]
     */
    public function haveAuthorizationToGlue(CustomerTransfer $customerTransfer): array
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
        $this->seeResponseCodeIs(HttpCode::CREATED);

        return $this->grabDataFromResponseByJsonPath('$.data.attributes')[0];
    }
}
