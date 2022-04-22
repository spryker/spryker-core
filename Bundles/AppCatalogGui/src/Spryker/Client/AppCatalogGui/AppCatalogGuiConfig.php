<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AppCatalogGui;

use Spryker\Client\Kernel\AbstractBundleConfig;

class AppCatalogGuiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getAopIdpUrl(): ?string
    {
        return getenv('SPRYKER_AOP_IDP_URL') ?: null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getAopClientId(): ?string
    {
        return getenv('SPRYKER_AOP_CLIENT_ID') ?: null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getAopClientSecret(): ?string
    {
        return getenv('SPRYKER_AOP_CLIENT_SECRET') ?: null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getAopAudience(): ?string
    {
        return getenv('SPRYKER_AOP_CLIENT_AUDIENCE') ?: null;
    }
}
