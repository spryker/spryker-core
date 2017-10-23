<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationBusinessFactory getFactory()
 */
class ProductRelationFacade extends AbstractFacade implements ProductRelationFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int
     */
    public function createProductRelation(ProductRelationTransfer $productRelationTransfer)
    {
        return $this->getFactory()
            ->createProductRelationWriter()
            ->saveRelation($productRelationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @throws \Exception
     * @throws \Throwable
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function updateProductRelation(ProductRelationTransfer $productRelationTransfer)
    {
        $this->getFactory()
            ->createProductRelationWriter()
            ->updateRelation($productRelationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationById($idProductRelation)
    {
        return $this->getFactory()
            ->createProductRelationReader()
            ->findProductRelationById($idProductRelation);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return bool
     */
    public function deleteProductRelation($idProductRelation)
    {
        return $this->getFactory()
            ->createProductRelationWriter()
            ->deleteProductRelation($idProductRelation);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer[]
     */
    public function getProductRelationTypeList()
    {
         return $this->getFactory()
             ->createProductRelationReader()
             ->getProductRelationTypeList();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function activateProductRelation($idProductRelation)
    {
        $this->getFactory()
            ->createProductRelationActivator()
            ->activate($idProductRelation);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function deactivateProductRelation($idProductRelation)
    {
        $this->getFactory()
            ->createProductRelationActivator()
            ->deactivate($idProductRelation);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function rebuildRelations()
    {
        $this->getFactory()
            ->createProductRelationUpdater()
            ->rebuildRelations();
    }
}
