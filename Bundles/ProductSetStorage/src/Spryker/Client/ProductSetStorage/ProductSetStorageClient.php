<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductSetStorage\ProductSetStorageFactory getFactory()
 */
class ProductSetStorageClient extends AbstractClient implements ProductSetStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function mapProductSetStorageDataToTransfer(array $productSetStorageData)
    {
        return $this
            ->getFactory()
            ->createProductSetStorageMapper()
            ->mapDataToTransfer($productSetStorageData);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductSet
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer|null
     */
    public function getProductSetByIdProductSet($idProductSet, $localeName)
    {
        return $this->getFactory()
            ->createProductSetStorage()
            ->getProductSetByIdProductSet($idProductSet, $localeName);
    }
}
