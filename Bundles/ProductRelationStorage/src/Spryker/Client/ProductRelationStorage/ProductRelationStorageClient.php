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
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findRelatedProducts($idProductAbstract, $localeName)
    {
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
     *
     * @return int[]
     */
    public function findRelatedAbstractProductIds(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createRelatedProductReader()
            ->findRelatedAbstractProductIds($idProductAbstract);
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
        return $this->getFactory()
            ->createUpSellingProductReader()
            ->findUpSellingAbstractProductIds($quoteTransfer);
    }
}
