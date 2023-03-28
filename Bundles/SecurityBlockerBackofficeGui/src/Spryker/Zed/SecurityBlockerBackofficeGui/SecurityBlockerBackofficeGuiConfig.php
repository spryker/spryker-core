<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityBlockerBackofficeGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityBlockerBackofficeGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Client\SecurityBlockerBackoffice\SecurityBlockerBackofficeConfig::BACKOFFICE_USER_SECURITY_BLOCKER_ENTITY_TYPE
     *
     * @var string
     */
    protected const SECURITY_BLOCKER_BACK_OFFICE_USER_ENTITY_TYPE = 'back-office-user';

    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Plugin\Security\UserSecurityPlugin::PATH_LOGIN_CHECK
     *
     * @var string
     */
    protected const BACK_OFFICE_LOGIN_CHECK_URL = 'login_check';

    /**
     * Specification:
     * - Returns Backoffice user entity type.
     *
     * @api
     *
     * @return string
     */
    public function getSecurityBlockerBackofficeUserEntityType(): string
    {
        return static::SECURITY_BLOCKER_BACK_OFFICE_USER_ENTITY_TYPE;
    }

    /**
     * Specification:
     * - Returns login check URL for Backoffice user.
     *
     * @api
     *
     * @return string
     */
    public function getBackofficeUserLoginCheckUrl(): string
    {
        return static::BACK_OFFICE_LOGIN_CHECK_URL;
    }
}
