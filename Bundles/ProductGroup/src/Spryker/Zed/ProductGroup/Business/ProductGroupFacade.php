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

    // TODO: we'll need add new touch entry for groups and on CUD events only extend existing search documents and create new redis entries (OR just touch related products?)

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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

    // TODO: Add facade method to get all groups of a product

}
