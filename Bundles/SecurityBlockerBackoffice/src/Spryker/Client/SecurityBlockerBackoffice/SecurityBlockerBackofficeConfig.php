<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerBackoffice;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SecurityBlockerBackoffice\SecurityBlockerBackofficeConstants;

class SecurityBlockerBackofficeConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const BACKOFFICE_USER_SECURITY_BLOCKER_ENTITY_TYPE = 'back-office-user';

    /**
     * Specification:
     * - Returns security blocker Backoffice entity type.
     *
     * @api
     *
     * @return string
     */
    public function getBackofficeUserSecurityBlockerEntityType(): string
    {
        return static::BACKOFFICE_USER_SECURITY_BLOCKER_ENTITY_TYPE;
    }

    /**
     * Specification:
     * - Returns backoffice user time for block in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getBackofficeUserBlockForSeconds(): int
    {
        return $this->get(SecurityBlockerBackofficeConstants::BACKOFFICE_USER_BLOCK_FOR_SECONDS, 0);
    }

    /**
     * Specification:
     * - Returns backoffice user blocking TTL in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getBackofficeUserBlockingTTL(): int
    {
        return $this->get(SecurityBlockerBackofficeConstants::BACKOFFICE_USER_BLOCKING_TTL, 0);
    }

    /**
     * Specification:
     * - Returns backoffice user number of attempts configuration.
     *
     * @api
     *
     * @return int
     */
    public function getBackofficeUserBlockingNumberOfAttempts(): int
    {
        return $this->get(SecurityBlockerBackofficeConstants::BACKOFFICE_USER_BLOCKING_NUMBER_OF_ATTEMPTS, 0);
    }
}
