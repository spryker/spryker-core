<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Vault\Business\Reader\VaultReader;
use Spryker\Zed\Vault\Business\Reader\VaultReaderInterface;
use Spryker\Zed\Vault\Business\Writer\VaultWriter;
use Spryker\Zed\Vault\Business\Writer\VaultWriterInterface;
use Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface;
use Spryker\Zed\Vault\VaultDependencyProvider;

/**
 * @method \Spryker\Zed\Vault\VaultConfig getConfig()
 * @method \Spryker\Zed\Vault\Persistence\VaultEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Vault\Persistence\VaultRepositoryInterface getRepository()
 */
class VaultBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Vault\Business\Reader\VaultReaderInterface
     */
    public function createVaultReader(): VaultReaderInterface
    {
        return new VaultReader(
            $this->getConfig(),
            $this->getUtilEncryptionService(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Vault\Business\Writer\VaultWriterInterface
     */
    public function createVaultWriter(): VaultWriterInterface
    {
        return new VaultWriter(
            $this->getConfig(),
            $this->getUtilEncryptionService(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceInterface
     */
    public function getUtilEncryptionService(): VaultToUtilEncryptionServiceInterface
    {
        return $this->getProvidedDependency(VaultDependencyProvider::SERVICE_UTIL_ENCRYPTION);
    }
}
