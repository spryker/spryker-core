<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Vault\Business\VaultBusinessFactory getFactory()
 * @method \Spryker\Zed\Vault\Persistence\VaultEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Vault\Persistence\VaultRepositoryInterface getRepository()
 */
class VaultFacade extends AbstractFacade implements VaultFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $dataType
     * @param string $dataKey
     * @param string $data
     *
     * @return bool
     */
    public function store(string $dataType, string $dataKey, string $data): bool
    {
        return $this->getFactory()
            ->createVaultWriter()
            ->store($dataType, $dataKey, $data);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $dataType
     * @param string $dataKey
     *
     * @return string|null
     */
    public function retrieve(string $dataType, string $dataKey): ?string
    {
        return $this->getFactory()
            ->createVaultReader()
            ->retrieve($dataType, $dataKey);
    }
}
