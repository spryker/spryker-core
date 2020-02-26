<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductRelationStorage\ProductRelationStorageFactory getFactory()
 */
class ProductRelationStorageClient extends AbstractClient implements ProductRelationStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findRelatedProducts($idProductAbstract, $localeName, ?string $storeName = null)
    {
        if (!$storeName) {
            trigger_error('Pass the $storeName parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createRelatedProductReader()
            ->findRelatedProducts($idProductAbstract, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findUpSellingProducts(QuoteTransfer $quoteTransfer, $localeName)
    {
        if (!$quoteTransfer->getStore()) {
            trigger_error('Pass the QuoteTransfer.Store parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createUpSellingProductReader()
            ->findUpSellingProducts($quoteTransfer, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string|null $storeName
     *
     * @return int[]
     */
    public function findRelatedAbstractProductIds(int $idProductAbstract, ?string $storeName = null): array
    {
        if (!$storeName) {
            trigger_error('Pass the $storeName parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createRelatedProductReader()
            ->findRelatedAbstractProductIds($idProductAbstract, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    public function findUpSellingAbstractProductIds(QuoteTransfer $quoteTransfer): array
    {
        if (!$quoteTransfer->getStore()) {
            trigger_error('Pass the QuoteTransfer.Store parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createUpSellingProductReader()
            ->findUpSellingAbstractProductIds($quoteTransfer);
    }
}
