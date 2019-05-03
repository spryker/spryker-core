<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Business\Reader;

use Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface;
use Spryker\Zed\Vault\Persistence\VaultRepositoryInterface;
use Spryker\Zed\Vault\VaultConfig;

class VaultReader implements VaultReaderInterface
{
    /**
     * @var \Spryker\Zed\Vault\VaultConfig
     */
    protected $vaultConfig;

    /**
     * @var \Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface
     */
    protected $utilEncryptionService;

    /**
     * @var \Spryker\Zed\Vault\Persistence\VaultRepositoryInterface
     */
    protected $vaultRepository;

    /**
     * @param \Spryker\Zed\Vault\VaultConfig $vaultConfig
     * @param \Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface $utilEncryptionService
     * @param \Spryker\Zed\Vault\Persistence\VaultRepositoryInterface $vaultRepository
     */
    public function __construct(
        VaultConfig $vaultConfig,
        VaultToUtilEncryptionServiceInterface $utilEncryptionService,
        VaultRepositoryInterface $vaultRepository
    ) {
        $this->vaultConfig = $vaultConfig;
        $this->utilEncryptionService = $utilEncryptionService;
        $this->vaultRepository = $vaultRepository;
    }

    /**
     * @param string $dataType
     * @param string $dataKey
     *
     * @return string|null
     */
    public function retrieve(string $dataType, string $dataKey): ?string
    {
        $vaultTransfer = $this->vaultRepository->findVaultByDataTypeAndKey($dataType, $dataKey);

        if (!$vaultTransfer) {
            return $vaultTransfer;
        }

        $encryptionKey = $this->vaultConfig->getEncryptionKeyPerType($dataKey);

        return $this->utilEncryptionService->decrypt(
            $vaultTransfer->getCipherText(),
            $vaultTransfer->getInitialVector(),
            $encryptionKey
        );
    }
}
