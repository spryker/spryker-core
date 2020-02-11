<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business;

use Generated\Shared\Transfer\ProductGroupTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductGroup\Business\ProductGroupBusinessFactory getFactory()
 */
class ProductGroupFacade extends AbstractFacade implements ProductGroupFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function createProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        return $this->getFactory()
            ->createProductGroupCreator()
            ->createProductGroup($productGroupTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer|null
     */
    public function findProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        return $this->getFactory()
            ->createProductGroupReader()
            ->findProductGroup($productGroupTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function updateProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        return $this->getFactory()
            ->createProductGroupUpdater()
            ->updateProductGroup($productGroupTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function extendProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        return $this->getFactory()
            ->createProductGroupExpander()
            ->extendProductGroup($productGroupTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function removeFromProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        return $this->getFactory()
            ->createProductGroupReducer()
            ->removeFromProductGroup($productGroupTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    public function deleteProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        $this->getFactory()
            ->createProductGroupDeleter()
            ->deleteProductGroup($productGroupTransfer);
    }
}
