<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @api
 *
 * @method \Spryker\Zed\ProductLabel\Business\ProductLabelBusinessFactory getFactory()
 */
class ProductLabelFacade extends AbstractFacade implements ProductLabelFacadeInterface
{

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @throws \Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function readLabel($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->read($idProductLabel);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function readAllLabels()
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->readAll();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function readLabelsForAbstractProduct($idProductAbstract)
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->readAllForAbstractProduct($idProductAbstract);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function createLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $this
            ->getFactory()
            ->createLabelCreator()
            ->create($productLabelTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function updateLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $this
            ->getFactory()
            ->createLabelUpdater()
            ->update($productLabelTransfer);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return int[]
     */
    public function readAbstractProductRelationsForLabel($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createAbstractProductRelationReader()
            ->readForProductLabel($idProductLabel);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    public function addAbstractProductRelationsForLabel($idProductLabel, array $idsProductAbstract)
    {
        $this
            ->getFactory()
            ->createAbstractProductRelationWriter()
            ->addRelations($idProductLabel, $idsProductAbstract);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    public function removeAbstractProductRelationsForLabel($idProductLabel, array $idsProductAbstract)
    {
        $this
            ->getFactory()
            ->createAbstractProductRelationDeleter()
            ->removeRelations($idProductLabel, $idsProductAbstract);
    }

    /**
     * @api
     *
     * @return void
     */
    public function checkLabelValidityDateRangeAndTouch()
    {
        $this
            ->getFactory()
            ->createLabelValidityUpdater()
            ->checkAndTouchAllLabels();
    }

}
