<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Generated\Shared\Transfer\ProductReplacementStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductAlternativeStorage\ProductAlternativeStorageFactory getFactory()
 */
class ProductAlternativeStorageClient extends AbstractClient implements ProductAlternativeStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeStorageTransfer|null
     */
    public function findProductAlternativeStorage(string $concreteSku): ?ProductAlternativeStorageTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeStorageReader()
            ->findProductAlternativeStorage($concreteSku);
    }

    /**
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(ProductViewTransfer $productViewTransfer): bool
    {
        return $this->getFactory()
            ->createAlternativeProductApplicableCheck()
            ->isAlternativeProductApplicable($productViewTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getConcreteAlternativeProducts(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->getConcreteAlternativeProducts($productViewTransfer, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getAlternativeProducts(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->getAlternativeProducts($productViewTransfer, $localeName);
    }
}
