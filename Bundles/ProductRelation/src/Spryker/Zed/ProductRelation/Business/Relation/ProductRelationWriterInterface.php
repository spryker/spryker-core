<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation;

use Generated\Shared\Transfer\ProductRelationTransfer;

interface ProductRelationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @throws \Throwable
     * @throws \Exception
     *
     * @return int
     */
    public function saveRelation(ProductRelationTransfer $productRelationTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @throws \Exception
     * @throws \Throwable
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function updateRelation(ProductRelationTransfer $productRelationTransfer);

    /**
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return bool
     */
    public function deleteProductRelation($idProductRelation);
}
