<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthWarehouseConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const SCOPE_WAREHOUSE = 'warehouse';

    /**
     * @api
     *
     * @var string
     */
    public const WAREHOUSE_GRANT_TYPE = 'Warehouse';

    /**
     * @api
     *
     * @var string
     */
    public const WAREHOUSE_TOKEN_TTL = 'P7D';

    /**
     * @api
     *
     * @return list<string>
     */
    public function getWarehouseScopes(): array
    {
        return [
            static::SCOPE_WAREHOUSE,
        ];
    }

    /**
     * Specification:
     * - Returns a list of user scopes that are allowed to be authorized.
     * - If empty, all user scopes are allowed.
     *
     * @api
     *
     * @return list<string>
     */
    public function getAllowedUserScopes(): array
    {
        return [];
    }
}
