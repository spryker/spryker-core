<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui;

use Spryker\Shared\AppCatalogGui\AppCatalogGuiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AppCatalogGuiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getAppCatalogScriptUrl(): string
    {
        return $this->get(AppCatalogGuiConstants::APP_CATALOG_SCRIPT_URL, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthAudience(): string
    {
        return $this->get(AppCatalogGuiConstants::OAUTH_OPTION_AUDIENCE, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthProviderName(): string
    {
        return $this->get(AppCatalogGuiConstants::OAUTH_PROVIDER_NAME, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthGrantType(): string
    {
        return $this->get(AppCatalogGuiConstants::OAUTH_GRANT_TYPE, '');
    }
}
