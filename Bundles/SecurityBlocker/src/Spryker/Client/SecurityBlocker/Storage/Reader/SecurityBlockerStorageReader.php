<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Storage\Reader;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use Spryker\Client\SecurityBlocker\Dependency\Service\SecurityBlockerToUtilEncodingServiceInterface;
use Spryker\Client\SecurityBlocker\Redis\SecurityBlockerRedisWrapperInterface;
use Spryker\Client\SecurityBlocker\SecurityBlockerConfig;
use Spryker\Client\SecurityBlocker\Storage\KeyBuilder\SecurityBlockerStorageKeyBuilderInterface;

class SecurityBlockerStorageReader implements SecurityBlockerStorageReaderInterface
{
    /**
     * @var \Spryker\Client\SecurityBlocker\Redis\SecurityBlockerRedisWrapperInterface
     */
    protected $securityBlockerRedisWrapper;

    /**
     * @var \Spryker\Client\SecurityBlocker\Storage\KeyBuilder\SecurityBlockerStorageKeyBuilderInterface
     */
    protected $securityBlockerStorageKeyBuilder;

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
     * @param \Spryker\Client\SecurityBlocker\Storage\KeyBuilder\SecurityBlockerStorageKeyBuilderInterface $securityBlockerStorageKeyBuilder
     * @param \Spryker\Client\SecurityBlocker\Dependency\Service\SecurityBlockerToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\SecurityBlocker\SecurityBlockerConfig $securityBlockerConfig
     */
    public function __construct(
        SecurityBlockerRedisWrapperInterface $securityBlockerRedisWrapper,
        SecurityBlockerStorageKeyBuilderInterface $securityBlockerStorageKeyBuilder,
        SecurityBlockerToUtilEncodingServiceInterface $utilEncodingService,
        SecurityBlockerConfig $securityBlockerConfig
    ) {
        $this->securityBlockerRedisWrapper = $securityBlockerRedisWrapper;
        $this->securityBlockerStorageKeyBuilder = $securityBlockerStorageKeyBuilder;
        $this->utilEncodingService = $utilEncodingService;
        $this->securityBlockerConfig = $securityBlockerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer
     */
    public function getLoginAttemptCount(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer
    {
        $storageKey = $this->securityBlockerStorageKeyBuilder->getStorageKey($securityCheckAuthContextTransfer);
        $numberOfAttempts = (int)$this->securityBlockerRedisWrapper->get($storageKey);

        $securityCheckAuthResponseTransfer = (new SecurityCheckAuthResponseTransfer())
            ->setSecurityCheckAuthContext($securityCheckAuthContextTransfer)
            ->setIsBlocked(false);

        if (!$numberOfAttempts) {
            return $securityCheckAuthResponseTransfer;
        }

        $securityBlockerConfigurationSettingsTransfer = $this->securityBlockerConfig
            ->getSecurityBlockerConfigurationSettingsForType($securityCheckAuthContextTransfer->getTypeOrFail());

        return $securityCheckAuthResponseTransfer
            ->setNumberOfAttempts($numberOfAttempts)
            ->setBlockedFor($securityBlockerConfigurationSettingsTransfer->getBlockFor())
            ->setIsBlocked($numberOfAttempts >= $securityBlockerConfigurationSettingsTransfer->getNumberOfAttempts());
    }
}
