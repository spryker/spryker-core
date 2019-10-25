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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $labelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findLabelByLabelName(string $labelName): ?ProductLabelTransfer
    {
        return $this
            ->getFactory()
            ->createLabelReader()
            ->findProductLabelByName($labelName);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
