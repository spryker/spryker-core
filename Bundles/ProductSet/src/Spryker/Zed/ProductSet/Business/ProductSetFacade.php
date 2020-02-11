<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business;

use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductSet\Business\ProductSetBusinessFactory getFactory()
 */
class ProductSetFacade extends AbstractFacade implements ProductSetFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function createProductSet(ProductSetTransfer $productSetTransfer)
    {
        return $this->getFactory()
            ->createProductSetCreator()
            ->createProductSet($productSetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer|null
     */
    public function findProductSet(ProductSetTransfer $productSetTransfer)
    {
        return $this->getFactory()
            ->createProductSetReader()
            ->findProductSet($productSetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function updateProductSet(ProductSetTransfer $productSetTransfer)
    {
        return $this->getFactory()
            ->createProductSetUpdater()
            ->updateProductSet($productSetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function extendProductSet(ProductSetTransfer $productSetTransfer)
    {
        return $this->getFactory()
            ->createProductSetExpander()
            ->extendProductSet($productSetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function removeFromProductSet(ProductSetTransfer $productSetTransfer)
    {
        return $this->getFactory()
            ->createProductSetReducer()
            ->removeFromProductSet($productSetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    public function deleteProductSet(ProductSetTransfer $productSetTransfer)
    {
        $this->getFactory()
            ->createProductSetDeleter()
            ->deleteProductSet($productSetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer[] $productSetTransfers
     *
     * @return void
     */
    public function reorderProductSets(array $productSetTransfers)
    {
        $this->getFactory()
            ->createProductSetOrganizer()
            ->reorderProductSets($productSetTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedProductSetImageSets($idProductSet, $idLocale)
    {
        return $this->getFactory()
            ->createProductSetImageSetCombiner()
            ->getCombinedProductSetImageSets($idProductSet, $idLocale);
    }
}
