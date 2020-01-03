<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Business\Filter;

use Spryker\Zed\OauthPermission\OauthPermissionConfig;

class OauthUserIdentifierFilter implements OauthUserIdentifierFilterInterface
{
    /**
     * @var \Spryker\Zed\OauthPermission\OauthPermissionConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\OauthPermission\OauthPermissionConfig $config
     */
    public function __construct(OauthPermissionConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $userIdentifier
     *
     * @return array
     */
    public function filter(array $userIdentifier): array
    {
        return array_filter($userIdentifier, function ($key) {
            return !in_array($key, $this->config->getOauthUserIdentifierFilterKeys());
        }, ARRAY_FILTER_USE_KEY);
    }
}
