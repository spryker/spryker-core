<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductRelationCriteriaTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductSelectorTransfer;

interface ProductRelationGuiToProductRelationFacadeInterface
{
    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationById($idProductRelation);

    /**
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findUniqueProductRelation(
        ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
    ): ?ProductRelationTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int
     */
    public function createProductRelation(ProductRelationTransfer $productRelationTransfer);

    /**
     * @param int $idProductRelation
     *
     * @return bool
     */
    public function deleteProductRelation($idProductRelation);

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @throws \Exception
     * @throws \Throwable
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function updateProductRelation(ProductRelationTransfer $productRelationTransfer);

    /**
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function activateProductRelation($idProductRelation);

    /**
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function deactivateProductRelation($idProductRelation);

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductSelectorTransfer
     */
    public function findProductForProductSelector(int $idProductAbstract, int $idLocale): ProductSelectorTransfer;

    /**
     * @return array
     */
    public function getJavascriptFilters(): array;
}
