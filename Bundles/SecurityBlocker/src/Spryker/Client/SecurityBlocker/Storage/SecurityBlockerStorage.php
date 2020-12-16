<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Storage;

use Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
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
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @throws \Spryker\Client\SecurityBlocker\Exception\SecurityBlockerException
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer
     */
    public function incrementLoginAttempt(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer
    {
        $securityCheckAuthContextTransfer->requireType();

        $securityBlockerConfigurationSettingsTransfer = $this->securityBlockerConfig
            ->getSecurityBlockerConfigurationSettingsForType($securityCheckAuthContextTransfer->getTypeOrFail());
        $key = $this->getStorageKey($securityCheckAuthContextTransfer);

        $newValue = $this->updateStorage($key, $securityBlockerConfigurationSettingsTransfer);

        if (!$newValue) {
            throw new SecurityBlockerException(
                sprintf('Could not set redisKey: "%s" with value: "%s"', $key, $newValue)
            );
        }

        return (new SecurityCheckAuthResponseTransfer())
            ->setSecurityCheckAuthContext($securityCheckAuthContextTransfer)
            ->setNumberOfAttempts($newValue)
            ->setIsSuccessful($newValue < $securityBlockerConfigurationSettingsTransfer->getNumberOfAttempts());
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer
     */
    public function getLoginAttempt(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer
    {
        $securityCheckAuthContextTransfer->requireType();

        $storageKey = $this->getStorageKey($securityCheckAuthContextTransfer);
        $storageValue = $this->securityBlockerRedisWrapper->get($storageKey);

        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())
            ->setSecurityCheckAuthContext($securityCheckAuthContextTransfer);

        if (!$storageValue) {
            return $securityCheckAuthResponseTransfer->setIsSuccessful(true);
        }

        $numberOfAttempts = $this->utilEncodingService->decodeJson($storageValue, true);

        if (!$numberOfAttempts) {
            return $securityCheckAuthResponseTransfer->setIsSuccessful(false);
        }

        $securityBlockerConfigurationSettingsTransfer = $this->securityBlockerConfig
            ->getSecurityBlockerConfigurationSettingsForType($securityCheckAuthContextTransfer->getTypeOrFail());

        return $securityCheckAuthResponseTransfer
            ->setNumberOfAttempts($numberOfAttempts)
            ->setIsSuccessful($numberOfAttempts < $securityBlockerConfigurationSettingsTransfer->getNumberOfAttempts());
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return string
     */
    protected function getStorageKey(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): string
    {
        return $securityCheckAuthContextTransfer->getType()
            . static::KEY_PART_SEPARATOR
            . $securityCheckAuthContextTransfer->getIp()
            . static::KEY_PART_SEPARATOR
            . $securityCheckAuthContextTransfer->getAccount();
    }

    /**
     * @param string $storageKey
     * @param \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer $securityBlockerConfigurationSettingsTransfer
     *
     * @return int
     */
    protected function updateStorage(
        string $storageKey,
        SecurityBlockerConfigurationSettingsTransfer $securityBlockerConfigurationSettingsTransfer
    ): int {
        $existingValue = (int)$this->securityBlockerRedisWrapper->get($storageKey);
        $newValue = $existingValue + 1;

        if ($existingValue && $newValue < $securityBlockerConfigurationSettingsTransfer->getNumberOfAttempts()) {
            $incrResult = $this->securityBlockerRedisWrapper->incr($storageKey);

            return $incrResult ? $newValue : 0;
        }

        $ttl = !$existingValue
            ? $securityBlockerConfigurationSettingsTransfer->getTtlOrFail()
            : $securityBlockerConfigurationSettingsTransfer->getBlockForOrFail();

        $setResult = $this->securityBlockerRedisWrapper->setex($storageKey, $ttl, (string)$newValue);

        return $setResult ? $newValue : 0;
    }
}
