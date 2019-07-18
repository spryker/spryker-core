<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class StoresRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_STORES = 'stores';
    public const RESPONSE_CODE_STORE_NOT_FOUND = '601';
    public const RESPONSE_MESSAGE_STORE_NOT_FOUND = 'Store not found.';
}
