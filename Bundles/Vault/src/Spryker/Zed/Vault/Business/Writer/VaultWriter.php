<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Business\Writer;

use Generated\Shared\Transfer\VaultTransfer;
use Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface;
use Spryker\Zed\Vault\Persistence\VaultEntityManagerInterface;
use Spryker\Zed\Vault\VaultConfig;

class VaultWriter implements VaultWriterInterface
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
     * @var \Spryker\Zed\Vault\Persistence\VaultEntityManagerInterface
     */
    protected $vaultEntityManager;

    /**
     * @param \Spryker\Zed\Vault\VaultConfig $vaultConfig
     * @param \Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface $utilEncryptionService
     * @param \Spryker\Zed\Vault\Persistence\VaultEntityManagerInterface $vaultEntityManager
     */
    public function __construct(
        VaultConfig $vaultConfig,
        VaultToUtilEncryptionServiceInterface $utilEncryptionService,
        VaultEntityManagerInterface $vaultEntityManager
    ) {
        $this->vaultConfig = $vaultConfig;
        $this->utilEncryptionService = $utilEncryptionService;
        $this->vaultEntityManager = $vaultEntityManager;
    }

    /**
     * @param string $dataType
     * @param string $dataKey
     * @param string $data
     *
     * @return bool
     */
    public function store(string $dataType, string $dataKey, string $data): bool
    {
        $encryptionKey = $this->vaultConfig->getEncryptionKeyPerType($dataType);
        $encryptInitVector = $this->utilEncryptionService->generateEncryptInitVector();
        $encryptedString = $this->utilEncryptionService->encrypt($data, $encryptInitVector, $encryptionKey);

        $vaultTransfer = (new VaultTransfer())
            ->setCipherText($encryptedString)
            ->setDataKey($dataKey)
            ->setInitialVector($encryptInitVector)
            ->setDataType($dataType);

        return $this->vaultEntityManager->createVault($vaultTransfer);
    }
}
