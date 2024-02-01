<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouseUser;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthWarehouseUserConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const SCOPE_WAREHOUSE_USER = 'warehouse-user';

    /**
     * Specification:
     * - Returns the user scope specific to a warehouse user.
     *
     * @api
     *
     * @return string
     */
    public function getWarehouseUserScope(): string
    {
        return static::SCOPE_WAREHOUSE_USER;
    }

    /**
     * Specification:
     * - Returns a list of configurations for endpoints accessible to warehouse users.
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
    public function getAllowedForWarehouseUserPaths(): array
    {
        return [];
    }
}
