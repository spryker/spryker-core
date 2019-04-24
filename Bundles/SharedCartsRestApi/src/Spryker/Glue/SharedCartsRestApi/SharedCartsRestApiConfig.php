<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class SharedCartsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_SHARED_CARTS = 'shared-carts';
    public const RESPONSE_CODE_CANT_FIND_SHARED_CARTS = '301';
    public const RESPONSE_DETAIL_CANT_FIND_SHARED_CARTS = 'Shared carts are not found.';
    public const RESPONSE_CODE_SHARED_CARTS_UUID_IS_NOT_SPECIFIED = '311';
    public const RESPONSE_DETAIL_SHARED_CARTS_UUID_IS_NOT_SPECIFIED = 'Shared carts UUID are not specified.';
}
