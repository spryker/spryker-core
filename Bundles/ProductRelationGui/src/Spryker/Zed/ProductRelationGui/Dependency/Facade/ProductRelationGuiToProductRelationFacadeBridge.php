<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductRelationCriteriaTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;

class ProductRelationGuiToProductRelationFacadeBridge implements ProductRelationGuiToProductRelationFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @param \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface $productRelationFacade
     */
    public function __construct($productRelationFacade)
    {
        $this->productRelationFacade = $productRelationFacade;
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationById($idProductRelation)
    {
        return $this->productRelationFacade->findProductRelationById($idProductRelation);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationByCriteria(ProductRelationCriteriaTransfer $productRelationCriteriaTransfer): ?ProductRelationTransfer
    {
        return $this->productRelationFacade->findProductRelationByCriteria($productRelationCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int
     */
    public function createProductRelation(ProductRelationTransfer $productRelationTransfer)
    {
        return $this->productRelationFacade->createProductRelation($productRelationTransfer);
    }

    /**
     * @param int $idProductRelation
     *
     * @return bool
     */
    public function deleteProductRelation($idProductRelation)
    {
        return $this->productRelationFacade->deleteProductRelation($idProductRelation);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    public function updateProductRelation(ProductRelationTransfer $productRelationTransfer)
    {
        $this->productRelationFacade->updateProductRelation($productRelationTransfer);
    }

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    public function activateProductRelation($idProductRelation)
    {
        $this->productRelationFacade->activateProductRelation($idProductRelation);
    }

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    public function deactivateProductRelation($idProductRelation)
    {
        $this->productRelationFacade->deactivateProductRelation($idProductRelation);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    public function getProductWithCategories(int $idProductAbstract, int $idLocale): array
    {
        return $this->productRelationFacade->getProductWithCategories($idProductAbstract, $idLocale);
    }
}
