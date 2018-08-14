<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi;

use Spryker\Shared\Config\Config;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\UtilDateTime\UtilDateTimeConstants;

class StoresRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_STORES = 'stores';
    public const RESPONSE_CODE_CANT_FIND_STORE = '601';
    public const RESPONSE_DETAIL_CANT_FIND_STORE = 'Can`t find store';

    /**
     * @return mixed
     */
    public function getTimeZone()
    {
        return Config::get(UtilDateTimeConstants::DATE_TIME_ZONE);
    }
}
