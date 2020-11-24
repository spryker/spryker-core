<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityGuiConfig extends AbstractBundleConfig
{
    public const ROLE_BACK_OFFICE_USER = 'ROLE_BACK_OFFICE_USER';
    protected const HOME_PATH = '/';
    protected const LOGIN_PATH = '/security-gui/login';
    protected const PASSWORD_RESET_PATH = '/security-gui/password/reset';
    protected const BACKOFFICE_ROUTE_PATTERN = '^/';
    protected const IGNORABLE_ROUTE_PATTERN = '^/security-gui';

    /**
     * @api
     *
     * @return string
     */
    public function getUrlHome(): string
    {
        return static::HOME_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUrlLogin(): string
    {
        return static::LOGIN_PATH;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getIgnorablePaths(): ?string
    {
        return static::IGNORABLE_ROUTE_PATTERN;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBackofficeRoutePattern(): string
    {
        return static::BACKOFFICE_ROUTE_PATTERN;
    }
}
