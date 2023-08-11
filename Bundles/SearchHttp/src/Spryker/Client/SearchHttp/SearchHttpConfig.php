<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SearchHttp\SearchHttpConstants;

class SearchHttpConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getTenantIdentifier(): string
    {
        return $this->get(SearchHttpConstants::TENANT_IDENTIFIER, '');
    }
}
