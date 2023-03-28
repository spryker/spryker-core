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
    /**
     * @deprecated Use {@link \Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE} instead.
     *
     * @var string
     */
    protected const SECURITY_BLOCKER_AGENT_ENTITY_TYPE = 'agent';

    /**
     * @var string
     */
    protected const STORAGE_REDIS_CONNECTION_KEY = 'SECURITY_BLOCKER_REDIS';

    /**
     * @var int
     */
    protected const DEFAULT_BLOCKING_TTL = 600;

    /**
     * @var int
     */
    protected const DEFAULT_BLOCK_FOR = 300;

    /**
     * @var int
     */
    protected const DEFAULT_BLOCKING_NUMBER_OF_ATTEMPTS = 10;

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
     * @return array<string>
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
            ->setScheme($this->getZedSessionScheme())
            ->setHost($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_HOST))
            ->setPort($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_PORT))
            ->setDatabase($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_DATABASE))
            ->setPassword($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_PASSWORD, false))
            ->setIsPersistent($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_PERSISTENT_CONNECTION, false));
    }

    /**
     * @return array<string, mixed>
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
     * @deprecated Exists for BC reasons. Use {@link \Spryker\Client\SecurityBlockerStorefrontAgent\Plugin\SecurityBlocker\AgentSecurityBlockerConfigurationSettingsExpanderPlugin}.
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function getSecurityBlockerConfigurationSettings(): array
    {
        $sharedConfig = $this->getConfig();

        $ttl = $sharedConfig::hasValue(SecurityBlockerConstants::SECURITY_BLOCKER_AGENT_BLOCKING_TTL)
            ? $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_AGENT_BLOCKING_TTL) : null;
        $blockFor = $sharedConfig::hasValue(SecurityBlockerConstants::SECURITY_BLOCKER_AGENT_BLOCK_FOR)
            ? $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_AGENT_BLOCK_FOR) : null;
        $numberOfAttempts = $sharedConfig::hasValue(SecurityBlockerConstants::SECURITY_BLOCKER_AGENT_BLOCKING_NUMBER_OF_ATTEMPTS)
            ? $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_AGENT_BLOCKING_NUMBER_OF_ATTEMPTS) : null;

        return [
            static::SECURITY_BLOCKER_AGENT_ENTITY_TYPE => (new SecurityBlockerConfigurationSettingsTransfer())
                ->setTtl($ttl)
                ->setBlockFor($blockFor)
                ->setNumberOfAttempts($numberOfAttempts),
        ];
    }

    /**
     * Specification:
     * - Returns the default security configuration.
     * - Used as fallback for all the entity types if the type-specific configuration is not given.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer
     */
    public function getDefaultSecurityBlockerConfigurationSettings(): SecurityBlockerConfigurationSettingsTransfer
    {
        return (new SecurityBlockerConfigurationSettingsTransfer())
            ->setTtl($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_BLOCKING_TTL, static::DEFAULT_BLOCKING_TTL))
            ->setBlockFor($this->get(SecurityBlockerConstants::SECURITY_BLOCKER_BLOCK_FOR, static::DEFAULT_BLOCK_FOR))
            ->setNumberOfAttempts(
                $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_BLOCKING_NUMBER_OF_ATTEMPTS, static::DEFAULT_BLOCKING_NUMBER_OF_ATTEMPTS),
            );
    }

    /**
     * @deprecated Use $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_SCHEME) instead. Added for BC reason only.
     *
     * @return string
     */
    protected function getZedSessionScheme(): string
    {
        return $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_SCHEME, false) ?:
            $this->get(SecurityBlockerConstants::SECURITY_BLOCKER_REDIS_PROTOCOL);
    }
}
