<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthMerchantUser;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthMerchantUserConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const SCOPE_MERCHANT_USER = 'merchant-user';

    /**
     * Specification:
     * - Returns the user scope specific to a merchant user.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantUserScope(): string
    {
        return static::SCOPE_MERCHANT_USER;
    }

    /**
     * Specification:
     * - Returns a list of configurations for endpoints accessible to merchant users.
     * - Structure example:
     * [
     *      '/example' => [
     *          'isRegularExpression' => false,
     *      ],
     *      '/\/example\/.+/' => [
     *          'isRegularExpression' => true,
     *          'methods' => [
     *              'patch',
     *              'delete',
     *          ],
     *      ],
     * ]
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getAllowedForMerchantUserPaths(): array
    {
        return [];
    }
}
