<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ConfigurableBundleStorage\ConfigurableBundleStorageFactory getFactory()
 */
class ConfigurableBundleStorageClient extends AbstractClient implements ConfigurableBundleStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateStorageTransfer
    {
        return $this->getFactory()
            ->createConfigurableBundleStorageReader()
            ->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $configurableBundleTemplateUuid
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorageByUuid(string $configurableBundleTemplateUuid): ?ConfigurableBundleTemplateStorageTransfer
    {
        return $this->getFactory()
            ->createConfigurableBundleStorageReader()
            ->findConfigurableBundleTemplateStorageByUuid($configurableBundleTemplateUuid);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcreteStoragesBySkusForCurrentLocale(array $skus): array
    {
        return $this->getFactory()
            ->createProductConcreteStorageReader()
            ->getProductConcreteStoragesBySkusForCurrentLocale($skus);
    }
}
