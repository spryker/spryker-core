<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class WarehouseOauthBackendApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_WAREHOUSE_TOKENS = 'warehouse-tokens';

    /**
     * @api
     *
     * @uses \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig::WAREHOUSE_GRANT_TYPE
     *
     * @var string
     */
    public const WAREHOUSE_GRANT_TYPE = 'Warehouse';

    /**
     * @api
     *
     * @uses \Spryker\Glue\OauthBackendApi\OauthBackendApiConfig::RESOURCE_TOKEN
     *
     * @var string
     */
    public const RESOURCE_TOKEN = 'token';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAILS_OPERATION_IS_FORBIDDEN = 'Operation is forbidden.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_OPERATION_IS_FORBIDDEN = '5101';
}
