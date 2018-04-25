<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Psr\Log\LoggerInterface;
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
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findLabelById($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->findByIdProductLabel($idProductLabel);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAllLabels()
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
    public function findLabelsByIdProductAbstract($idProductAbstract)
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
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findLabelIdsByIdProductAbstract($idProductAbstract)
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->findAllLabelIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findActiveLabelIdsByIdProductAbstract($idProductAbstract)
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->findAllActiveLabelIdsByIdProductAbstract($idProductAbstract);
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
    public function findProductAbstractRelationsByIdProductLabel($idProductLabel)
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

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function updateDynamicProductLabelRelations(?LoggerInterface $logger = null)
    {
        $this->getFactory()
            ->createProductAbstractRelationUpdater($logger)
            ->updateProductLabelRelations();
    }
}
