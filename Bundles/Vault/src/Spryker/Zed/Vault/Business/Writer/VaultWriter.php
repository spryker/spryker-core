<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Business\Writer;

use Generated\Shared\Transfer\VaultDepositTransfer;
use Spryker\Zed\Vault\Business\Converter\InitialVectorConverterInterface;
use Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface;
use Spryker\Zed\Vault\Persistence\VaultEntityManagerInterface;
use Spryker\Zed\Vault\Persistence\VaultRepositoryInterface;
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
     * @var \Spryker\Zed\Vault\Persistence\VaultRepositoryInterface
     */
    protected $vaultRepository;

    /**
     * @var \Spryker\Zed\Vault\Business\Converter\InitialVectorConverterInterface
     */
    protected $initialVectorConverter;

    /**
     * @param \Spryker\Zed\Vault\VaultConfig $vaultConfig
     * @param \Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface $utilEncryptionService
     * @param \Spryker\Zed\Vault\Persistence\VaultEntityManagerInterface $vaultEntityManager
     * @param \Spryker\Zed\Vault\Persistence\VaultRepositoryInterface $vaultRepository
     * @param \Spryker\Zed\Vault\Business\Converter\InitialVectorConverterInterface $initialVectorConverter
     */
    public function __construct(
        VaultConfig $vaultConfig,
        VaultToUtilEncryptionServiceInterface $utilEncryptionService,
        VaultEntityManagerInterface $vaultEntityManager,
        VaultRepositoryInterface $vaultRepository,
        InitialVectorConverterInterface $initialVectorConverter
    ) {
        $this->vaultConfig = $vaultConfig;
        $this->utilEncryptionService = $utilEncryptionService;
        $this->vaultEntityManager = $vaultEntityManager;
        $this->vaultRepository = $vaultRepository;
        $this->initialVectorConverter = $initialVectorConverter;
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
        $encryptInitVector = $this->generateOpenSslEncryptInitVector();
        $encryptedString = $this->utilEncryptionService->encryptOpenSsl(
            $data,
            $encryptInitVector,
            $this->vaultConfig->getEncryptionKey()
        );

        $vaultDepositTransfer = $this->createVaultDepositTransfer($encryptedString, $dataKey, $encryptInitVector, $dataType);

        if ($this->vaultRepository->findVaultDepositByDataTypeAndKey($vaultDepositTransfer->getDataType(), $vaultDepositTransfer->getDataKey())) {
             $this->vaultEntityManager->updateVaultDeposit($vaultDepositTransfer);

             return true;
        }

        return $this->vaultEntityManager->createVaultDeposit($vaultDepositTransfer);
    }

    /**
     * @return string
     */
    protected function generateOpenSslEncryptInitVector(): string
    {
        if ($this->vaultConfig->useByteStringForEncryptionInitializationVector()) {
            return $this->utilEncryptionService->generateByteStringOpenSslEncryptInitVector();
        }

        return $this->utilEncryptionService->generateOpenSslEncryptInitVector();
    }

    /**
     * @param string $encryptedString
     * @param string $dataKey
     * @param string $encryptInitVector
     * @param string $dataType
     *
     * @return \Generated\Shared\Transfer\VaultDepositTransfer
     */
    protected function createVaultDepositTransfer(string $encryptedString, string $dataKey, string $encryptInitVector, string $dataType): VaultDepositTransfer
    {
        if ($this->vaultConfig->useByteStringForEncryptionInitializationVector()) {
            $encryptInitVector = $this->initialVectorConverter->convertToHex($encryptInitVector);
        }

        return (new VaultDepositTransfer())
            ->setCipherText($encryptedString)
            ->setDataKey($dataKey)
            ->setInitialVector($encryptInitVector)
            ->setDataType($dataType);
    }
}
