<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductReplacementStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductAlternativeStorage\ProductAlternativeStorageFactory getFactory()
 */
class ProductAlternativeStorageClient extends AbstractClient implements ProductAlternativeStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer|null
     */
    public function findProductAlternativeStorage(string $concreteSku): ?ProductAlternativeTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeStorageReader()
            ->findProductAlternativeStorage($concreteSku);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductReplacementStorageTransfer|null
     */
    public function findProductReplacementForStorage(string $sku): ?ProductReplacementStorageTransfer
    {
        return $this->getFactory()
            ->createProductReplacementStorageReader()
            ->findProductAlternativeStorage($sku);
    }
}
