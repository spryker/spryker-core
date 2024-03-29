<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductLabelTransfer;

interface ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function createLabel(ProductLabelTransfer $productLabelTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function updateLabel(ProductLabelTransfer $productLabelTransfer);

    /**
     * @param string $labelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findLabelByLabelName(string $labelName): ?ProductLabelTransfer;

    /**
     * @param int $idProductLabel
     * @param array<int> $idsProductAbstract
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel($idProductLabel, array $idsProductAbstract);

    /**
     * @param int $idProductLabel
     * @param array<int> $idsProductAbstract
     *
     * @return void
     */
    public function addAbstractProductRelationsForLabel($idProductLabel, array $idsProductAbstract);

    /**
     * @param int $idsProductAbstract
     *
     * @return array<int>
     */
    public function findActiveLabelIdsByIdProductAbstract(int $idsProductAbstract);

    /**
     * @param int $idProductLabel
     *
     * @return array<int>
     */
    public function findProductAbstractRelationsByIdProductLabel(int $idProductLabel);
}
