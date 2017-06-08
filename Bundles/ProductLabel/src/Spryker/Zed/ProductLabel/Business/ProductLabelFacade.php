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
     * {@inheritdoc}
     *
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
            ->getByIdProductLabel($idProductLabel);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function readAllLabels()
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->findAll();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function readLabelsForProductAbstract($idProductAbstract)
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->findAllByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @throws \Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductLabel
     *
     * @return int[]
     */
    public function readProductAbstractRelationsForLabel($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createProductAbstractRelationReader()
            ->findIdsProductAbstractByIdProductLabel($idProductLabel);
    }

    /**
     * {@inheritdoc}
     *
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
            ->createProductAbstractRelationWriter()
            ->addRelations($idProductLabel, $idsProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel($idProductLabel, array $idsProductAbstract)
    {
        $this
            ->getFactory()
            ->createProductAbstractRelationDeleter()
            ->removeRelations($idProductLabel, $idsProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
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
