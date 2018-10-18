<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;

interface LabelReaderInterface
{
    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findByIdProductLabel($idProductLabel);

    /**
     * @param string $labelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelByName($labelName): ?ProductLabelTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAll();

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAllByIdProductAbstract($idProductAbstract);

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findAllLabelIdsByIdProductAbstract($idProductAbstract);

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findAllActiveLabelIdsByIdProductAbstract($idProductAbstract);
}
