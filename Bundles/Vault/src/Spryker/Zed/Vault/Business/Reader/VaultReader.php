<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Business\Reader;

use Generated\Shared\Transfer\VaultDepositTransfer;
use Spryker\Zed\Vault\Business\Converter\InitialVectorConverterInterface;
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
     * @var \Spryker\Zed\Vault\Business\Converter\InitialVectorConverterInterface
     */
    protected $initialVectorConverter;

    /**
     * @param \Spryker\Zed\Vault\VaultConfig $vaultConfig
     * @param \Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface $utilEncryptionService
     * @param \Spryker\Zed\Vault\Persistence\VaultRepositoryInterface $vaultRepository
     * @param \Spryker\Zed\Vault\Business\Converter\InitialVectorConverterInterface $initialVectorConverter
     */
    public function __construct(
        VaultConfig $vaultConfig,
        VaultToUtilEncryptionServiceInterface $utilEncryptionService,
        VaultRepositoryInterface $vaultRepository,
        InitialVectorConverterInterface $initialVectorConverter
    ) {
        $this->vaultConfig = $vaultConfig;
        $this->utilEncryptionService = $utilEncryptionService;
        $this->vaultRepository = $vaultRepository;
        $this->initialVectorConverter = $initialVectorConverter;
    }

    /**
     * @param string $dataType
     * @param string $dataKey
     *
     * @return string|null
     */
    public function retrieve(string $dataType, string $dataKey): ?string
    {
        $vaultDepositTransfer = $this->vaultRepository->findVaultDepositByDataTypeAndKey($dataType, $dataKey);

        if (!$vaultDepositTransfer) {
            return null;
        }

        return $this->utilEncryptionService->decryptOpenSsl(
            $vaultDepositTransfer->getCipherText(),
            $this->getInitializingVector($vaultDepositTransfer),
            $this->vaultConfig->getEncryptionKey()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\VaultDepositTransfer $vaultDepositTransfer
     *
     * @return string
     */
    protected function getInitializingVector(VaultDepositTransfer $vaultDepositTransfer): string
    {
        if (!$this->vaultConfig->useByteStringForEncryptionInitializationVector()) {
            return $vaultDepositTransfer->getInitialVector();
        }

        return $this->initialVectorConverter->convertToBin($vaultDepositTransfer->getInitialVector());
    }
}
