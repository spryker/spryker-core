<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ApiConfig extends AbstractBundleConfig
{

    const ROUTE_PREFIX_API_REST = '/api/rest/';

    const FORMAT_TYPE = 'json';

    /**
     * @return int
     */
    public function getLimitPerPage()
    {
        return 20;
    }

    /**
     * @return int
     */
    public function getMaxLimitPerPage()
    {
        return 100;
    }

}
