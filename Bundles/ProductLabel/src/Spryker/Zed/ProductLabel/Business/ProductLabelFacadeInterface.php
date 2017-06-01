<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Generated\Shared\Transfer\ProductLabelTransfer;

interface ProductLabelFacadeInterface
{

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function readLabel($idProductLabel);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function readAllLabels();

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function readLabelsForAbstractProduct($idProductAbstract);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function createLabel(ProductLabelTransfer $productLabelTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function updateLabel(ProductLabelTransfer $productLabelTransfer);

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return int[]
     */
    public function readAbstractProductRelationsForLabel($idProductLabel);

    /**
     * @api
     *
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return mixed
     */
    public function addAbstractProductRelationsForLabel($idProductLabel, array $idsProductAbstract);

    /**
     * @api
     *
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return mixed
     */
    public function removeAbstractProductRelationsForLabel($idProductLabel, array $idsProductAbstract);

    /**
     * @api
     *
     * @return void
     */
    public function checkLabelValidityDateRangeAndTouch();

}
