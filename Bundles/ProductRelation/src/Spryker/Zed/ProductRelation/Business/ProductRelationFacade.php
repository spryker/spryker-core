<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductRelationCriteriaTransfer;
use Generated\Shared\Transfer\ProductRelationResponseTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface getEntityManager()
 */
class ProductRelationFacade extends AbstractFacade implements ProductRelationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function createProductRelation(ProductRelationTransfer $productRelationTransfer): ProductRelationResponseTransfer
    {
        return $this->getFactory()
            ->createProductRelationCreator()
            ->createProductRelation($productRelationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function updateProductRelation(ProductRelationTransfer $productRelationTransfer): ProductRelationResponseTransfer
    {
        return $this->getFactory()
            ->createProductRelationUpdater()
            ->updateProductRelation($productRelationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function findProductRelationById($idProductRelation): ProductRelationResponseTransfer
    {
        return $this->getFactory()
            ->createProductRelationReader()
            ->findProductRelationById($idProductRelation);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function deleteProductRelation(int $idProductRelation): ProductRelationResponseTransfer
    {
        return $this->getFactory()
            ->createProductRelationDeleter()
            ->deleteProductRelation($idProductRelation);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer[]
     */
    public function getProductRelationTypeList()
    {
         return $this->getRepository()->getProductRelationTypes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link updateProductRelation()} instead.
     *
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function activateProductRelation($idProductRelation)
    {
        $this->getFactory()
            ->createProductRelationActivator()
            ->activate($idProductRelation);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link updateProductRelation()} instead.
     *
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function deactivateProductRelation($idProductRelation)
    {
        $this->getFactory()
            ->createProductRelationActivator()
            ->deactivate($idProductRelation);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function rebuildRelations()
    {
        $this->getFactory()
            ->createProductRelationBuilder()
            ->rebuildRelations();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationByCriteria(
        ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
    ): ?ProductRelationTransfer {
        return $this->getRepository()->findProductRelationByCriteria($productRelationCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    public function getProductAbstractDataById(int $idProductAbstract, int $idLocale): array
    {
        return $this->getRepository()->getProductAbstractDataById($idProductAbstract, $idLocale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function getProductRelationsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getRepository()->getProductRelationsByProductAbstractIds($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productRelationIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductRelationIds(
        array $productRelationIds
    ): array {
        return $this->getRepository()->getProductAbstractIdsByProductRelationIds($productRelationIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function findProductRelationsForFilter(FilterTransfer $filterTransfer): array
    {
        return $this->getRepository()->findProductRelationsForFilter($filterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
     *
     * @return array
     */
    public function getStoresByProductRelationCriteria(
        ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
    ): array {
        return $this->getRepository()->getStoresByProductRelationCriteria($productRelationCriteriaTransfer);
    }
}
