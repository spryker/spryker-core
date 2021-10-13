<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser;

use Spryker\Shared\MerchantUser\MerchantUserConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantUserConfig extends AbstractBundleConfig
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     * @var string
     */
    public const USER_CREATION_DEFAULT_STATUS = 'blocked';

    /**
     * @api
     *
     * @return string
     */
    public function getUserCreationStatus(): string
    {
        return static::USER_CREATION_DEFAULT_STATUS;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function canUserHaveManyMerchants(): bool
    {
        return false;
    }

    /**
     * Specification:
     *  - Returns merchant portal application base url (scheme, host, port).
     *
     * @api
     *
     * @return string
     */
    public function getMerchantPortalBaseUrl(): string
    {
        return $this->get(MerchantUserConstants::BASE_URL_MP);
    }
}
