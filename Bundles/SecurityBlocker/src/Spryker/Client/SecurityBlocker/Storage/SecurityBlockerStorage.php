<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Storage;

use Generated\Shared\Transfer\AuthContextTransfer;
use Generated\Shared\Transfer\AuthResponseTransfer;
use Generated\Shared\Transfer\SecurityConfigurationSettingTransfer;
use Spryker\Client\SecurityBlocker\Dependency\Service\SecurityBlockerToUtilEncodingServiceInterface;
use Spryker\Client\SecurityBlocker\Exception\SecurityBlockerException;
use Spryker\Client\SecurityBlocker\Redis\SecurityBlockerRedisWrapperInterface;
use Spryker\Client\SecurityBlocker\SecurityBlockerConfig;

class SecurityBlockerStorage implements SecurityBlockerStorageInterface
{
    protected const KEY_PART_SEPARATOR = ':';

    /**
     * @var \Spryker\Client\SecurityBlocker\Redis\SecurityBlockerRedisWrapperInterface
     */
    protected $securityBlockerRedisWrapper;

    /**
     * @var \Spryker\Client\SecurityBlocker\SecurityBlockerConfig
     */
    protected $securityBlockerConfig;

    /**
     * @var \Spryker\Client\SecurityBlocker\Dependency\Service\SecurityBlockerToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\SecurityBlocker\Redis\SecurityBlockerRedisWrapperInterface $securityBlockerRedisWrapper
     * @param \Spryker\Client\SecurityBlocker\Dependency\Service\SecurityBlockerToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\SecurityBlocker\SecurityBlockerConfig $securityBlockerConfig
     */
    public function __construct(
        SecurityBlockerRedisWrapperInterface $securityBlockerRedisWrapper,
        SecurityBlockerToUtilEncodingServiceInterface $utilEncodingService,
        SecurityBlockerConfig $securityBlockerConfig
    ) {
        $this->securityBlockerRedisWrapper = $securityBlockerRedisWrapper;
        $this->utilEncodingService = $utilEncodingService;
        $this->securityBlockerConfig = $securityBlockerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @throws \Spryker\Client\SecurityBlocker\Exception\SecurityBlockerException
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function incrementLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        $authContextTransfer->requireType();

        $securityConfigurationSettings = $this->getSecurityConfigurationSettingsForType($authContextTransfer->getType());
        $key = $this->getStorageKey($authContextTransfer);

        $newValue = $this->incrementStorageKey($key, $securityConfigurationSettings);

        if (!$newValue) {
            throw new SecurityBlockerException(
                sprintf('Could not set redisKey: "%s" with value: "%s"', $key, $newValue)
            );
        }

        return (new AuthResponseTransfer())
            ->fromArray($authContextTransfer->toArray(), true)
            ->setCount($newValue)
            ->setIsSuccessful($newValue < $securityConfigurationSettings->getCount());
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return \Generated\Shared\Transfer\AuthResponseTransfer
     */
    public function getLoginAttempt(AuthContextTransfer $authContextTransfer): AuthResponseTransfer
    {
        $storageKey = $this->getStorageKey($authContextTransfer);
        $storageValue = $this->securityBlockerRedisWrapper->get($storageKey);

        if (!$storageValue) {
            return (new AuthResponseTransfer())
                ->fromArray($authContextTransfer->toArray(), true)
                ->setIsSuccessful(true);
        }

        $numberOfAttempts = $this->utilEncodingService->decodeJson($storageValue, true);

        if (!$numberOfAttempts) {
            return (new AuthResponseTransfer())
                ->fromArray($authContextTransfer->toArray(), true)
                ->setIsSuccessful(false);
        }

        $securityConfigurationSettings = $this->getSecurityConfigurationSettingsForType($authContextTransfer->getType());

        return (new AuthResponseTransfer())
            ->fromArray($authContextTransfer->toArray(), true)
            ->setCount($numberOfAttempts)
            ->setIsSuccessful($numberOfAttempts < $securityConfigurationSettings->getCount());
    }

    /**
     * @param \Generated\Shared\Transfer\AuthContextTransfer $authContextTransfer
     *
     * @return string
     */
    protected function getStorageKey(AuthContextTransfer $authContextTransfer): string
    {
        return $authContextTransfer->getType()
            . static::KEY_PART_SEPARATOR
            . $authContextTransfer->getIp()
            . static::KEY_PART_SEPARATOR
            . $authContextTransfer->getAccount();
    }

    /**
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\SecurityConfigurationSettingTransfer
     */
    protected function getSecurityConfigurationSettingsForType(string $type): SecurityConfigurationSettingTransfer
    {
        $securityConfigurationSettings = $this->securityBlockerConfig->getSecurityConfigurationSettings();
        if (!empty($securityConfigurationSettings[$type])) {
            return (new SecurityConfigurationSettingTransfer())
                ->fromArray($securityConfigurationSettings[$type], true);
        }

        return (new SecurityConfigurationSettingTransfer())
            ->fromArray($securityConfigurationSettings['default'], true);
    }

    /**
     * @param string $storageKey
     * @param \Generated\Shared\Transfer\SecurityConfigurationSettingTransfer $securityConfigurationSettingTransfer
     *
     * @return int
     */
    protected function incrementStorageKey(
        string $storageKey,
        SecurityConfigurationSettingTransfer $securityConfigurationSettingTransfer
    ): int {
        $existingValue = (int)$this->securityBlockerRedisWrapper->get($storageKey);

        if ($existingValue) {
            $incrResult = $this->securityBlockerRedisWrapper->incr($storageKey);

            return $incrResult ? ++$existingValue : 0;
        }

        $setResult = $this->securityBlockerRedisWrapper
            ->set($storageKey, '1', 'EX', $securityConfigurationSettingTransfer->getTtl());

        return $setResult ? 1 : 0;
    }
}
