<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;
use Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer;
use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SecurityBlocker\SecurityBlockerConstants;

class SecurityBlockerConfig extends AbstractBundleConfig
{
    public const SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE = 'customer';

    protected const REDIS_DEFAULT_DATABASE = 0;
    protected const STORAGE_REDIS_CONNECTION_KEY = 'SECURITY_BLOCKER_REDIS';
    protected const ENTITY_TYPE_DEFAULT = 'default';

    /**
     * Specification:
     * - Returns redis connection key used by the module.
     *
     * @api
     *
     * @return string
     */
    public function getRedisConnectionKey(): string
    {
        return static::STORAGE_REDIS_CONNECTION_KEY;
    }

    /**
     * Specification:
     * - Returns redis connection configuration used by the module.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RedisConfigurationTransfer
     */
    public function getRedisConnectionConfiguration(): RedisConfigurationTransfer
    {
        return (new RedisConfigurationTransfer())
            ->setDataSourceNames($this->getDataSourceNames())
            ->setConnectionCredentials($this->getConnectionCredentials())
            ->setClientOptions($this->getConnectionOptions());
    }

    /**
     * @return string[]
     */
    protected function getDataSourceNames(): array
    {
        return $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_DATA_SOURCE_NAMES, []);
    }

    /**
     * @return \Generated\Shared\Transfer\RedisCredentialsTransfer
     */
    protected function getConnectionCredentials(): RedisCredentialsTransfer
    {
        return (new RedisCredentialsTransfer())
            ->setProtocol($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_PROTOCOL))
            ->setHost($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_HOST))
            ->setPort($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_PORT))
            ->setDatabase($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_DATABASE, static::REDIS_DEFAULT_DATABASE))
            ->setPassword($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_PASSWORD, false))
            ->setIsPersistent($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_PERSISTENT_CONNECTION, false));
    }

    /**
     * @return array
     */
    protected function getConnectionOptions(): array
    {
        return $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_CONNECTION_OPTIONS, []);
    }

    /**
     * Specification:
     * - Returns the security configuration per type.
     *
     * @api
     *
     * @return mixed[]
     */
    public function getSecurityBlockerConfigurationSettings(): array
    {
        return [
            static::ENTITY_TYPE_DEFAULT => [
                'ttl' => $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_BLOCKING_TTL, 300),
                'numberOfAttempts' => $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_BLOCKING_NUMBER_OF_ATTEMPTS, 10),
            ],
        ];
    }

    /**
     * Specification:
     * - Returns the security configuration for the type.
     * - Falls back to the default type if no type-specific setting is found.
     *
     * @api
     *
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer
     */
    public function getSecurityBlockerConfigurationSettingsForType(string $type): SecurityBlockerConfigurationSettingsTransfer
    {
        $securityConfigurationSettings = $this->getSecurityBlockerConfigurationSettings();

        if (!empty($securityConfigurationSettings[$type])) {
            return (new SecurityBlockerConfigurationSettingsTransfer())
                ->fromArray($securityConfigurationSettings[$type], true);
        }

        return (new SecurityBlockerConfigurationSettingsTransfer())
            ->fromArray($securityConfigurationSettings[static::ENTITY_TYPE_DEFAULT], true);
    }
}
