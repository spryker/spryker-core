<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthUserConnectorConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @uses \Spryker\Zed\Oauth\OauthConfig::GRANT_TYPE_PASSWORD
     *
     * @var string
     */
    public const GRANT_TYPE_PASSWORD = 'password';

    /**
     * @var string
     */
    protected const SCOPE_USER = 'user';

    /**
     * Specification:
     * - Returns user scopes.
     *
     * @api
     *
     * @return array<string>
     */
    public function getUserScopes(): array
    {
        return [static::SCOPE_USER];
    }
}
