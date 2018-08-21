<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductResourceAliasStorage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PriceProductResourceAliasStorage\PriceProductResourceAliasStorageFactory getFactory()
 */
class PriceProductResourceAliasStorageClient extends AbstractClient implements PriceProductResourceAliasStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceProductAbstractStorageTransfer(string $sku): ?PriceProductStorageTransfer
    {
        return $this->getFactory()
            ->createPriceProductAbstractStorageReader()
            ->findPriceProductAbstractStorageTransfer($sku);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceProductConcreteStorageTransfer(string $sku): ?PriceProductStorageTransfer
    {
        return $this->getFactory()
            ->createPriceProductConcreteStorageReader()
            ->findPriceProductConcreteStorageTransfer($sku);
    }
}
