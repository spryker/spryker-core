<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelation;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationType;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductRelationWriter implements ProductRelationWriterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(
        ProductRelationToTouchInterface $touchFacade,
        ProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationToUtilEncodingInterface $utilEncodingService
    ) {
        $this->touchFacade = $touchFacade;
        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int
     */
    public function saveRelation(ProductRelationTransfer $productRelationTransfer)
    {
        $productRelationTransfer->requireProductRelationType();

        $productRelationTypeTransfer = $productRelationTransfer->getProductRelationType();

        return $this->handleDatabaseTransaction(function () use ($productRelationTransfer, $productRelationTypeTransfer) {
            return $this->executeSaveRelationTransaction($productRelationTransfer, $productRelationTypeTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     * @param \Generated\Shared\Transfer\ProductRelationTypeTransfer $productRelationTypeTransfer
     *
     * @return int
     */
    protected function executeSaveRelationTransaction(
        ProductRelationTransfer $productRelationTransfer,
        ProductRelationTypeTransfer $productRelationTypeTransfer
    ) {

        $productRelationTypeEntity = $this->findOrCreateProductRelationTypeEntity($productRelationTypeTransfer);
        $productRelationTypeEntity->save();

        $productRelationEntity = $this->mapProductRelationEntity($productRelationTransfer, $productRelationTypeEntity);
        $productRelationEntity->save();

        $abstractProductIds = $this->getRelatedProductAbstractIds($productRelationTransfer);

        $this->saveRelatedProducts($abstractProductIds, $productRelationEntity->getIdProductRelation());
        $this->touchRelationActive($productRelationTransfer->getFkProductAbstract());

        return $productRelationEntity->getIdProductRelation();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function updateRelation(ProductRelationTransfer $productRelationTransfer)
    {
        $this->assertUpdateRelation($productRelationTransfer);

        $productRelationEntity = $this->findProductRelationEntityById($productRelationTransfer->getIdProductRelation());

        if ($productRelationEntity === null) {
            throw new ProductRelationNotFoundException(
                sprintf(
                    'Product relation with id "%d" not found.',
                    $productRelationTransfer->getIdProductRelation()
                )
            );
        }

        $this->handleDatabaseTransaction(function () use ($productRelationTransfer, $productRelationEntity) {
            $this->executeUpdateRelationTransaction($productRelationTransfer, $productRelationEntity);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return void
     */
    protected function executeUpdateRelationTransaction(
        ProductRelationTransfer $productRelationTransfer,
        SpyProductRelation $productRelationEntity
    ) {
        $productRelationTypeTransfer = $productRelationTransfer->getProductRelationType();

        if ($productRelationTypeTransfer !== null) {
            $productRelationTypeEntity = $this->findOrCreateProductRelationTypeEntity($productRelationTypeTransfer);
            $productRelationTypeEntity->save();

            $productRelationTypeTransfer->setIdProductRelationType($productRelationTypeEntity->getIdProductRelationType());
        }

        $productRelationEntity = $this->updateProductRelationEntity($productRelationTransfer, $productRelationEntity);
        $productRelationEntity->save();

        $abstractProductIds = $this->getRelatedProductAbstractIds($productRelationTransfer);

        $this->removeRelatedProductsByIdRelation($productRelationTransfer->getIdProductRelation());
        $this->saveRelatedProducts($abstractProductIds, $productRelationEntity->getIdProductRelation());

        $this->touchRelationActive($productRelationTransfer->getFkProductAbstract());
    }

    /**
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return bool
     */
    public function deleteProductRelation($idProductRelation)
    {
        $productRelationEntity = $this->findProductRelationEntityById($idProductRelation);

        if ($productRelationEntity === null) {
            throw new ProductRelationNotFoundException(
                sprintf(
                    'Product relation with id "%d" not found.',
                    $productRelationEntity
                )
            );
        }

        $deleted = $this->handleDatabaseTransaction(function () use ($productRelationEntity) {
            return $this->executeDeleteProductRelationTransaction($productRelationEntity);
        });

        return $deleted;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return bool
     */
    protected function executeDeleteProductRelationTransaction(SpyProductRelation $productRelationEntity)
    {
        $this->removeRelatedProductsByIdRelation($productRelationEntity->getIdProductRelation());

        $this->touchFacade->touchDeleted(
            ProductRelationConstants::RESOURCE_TYPE_PRODUCT_RELATION,
            $productRelationEntity->getFkProductAbstract()
        );

        $productRelationEntity->delete();

        return $productRelationEntity->isDeleted();
    }

    /**
     * @param int $idProductRelation
     *
     * @return int
     */
    protected function removeRelatedProductsByIdRelation($idProductRelation)
    {
        return $this->productRelationQueryContainer
            ->queryProductRelationProductAbstractByIdProductRelation($idProductRelation)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return array
     */
    protected function getRelatedProductAbstractIds(ProductRelationTransfer $productRelationTransfer)
    {
        $productAbstracts = $this->findRuleMatchingProducts($productRelationTransfer);

        $productAbstractIds = [];
        foreach ($productAbstracts as $productAbstractEntity) {
            $productAbstractIds[] = $productAbstractEntity->getIdProductAbstract();
        }

        return $productAbstractIds;
    }

    /**
     * @param array $abstractProductIds
     * @param int $idProductRelation
     *
     * @return void
     */
    protected function saveRelatedProducts(array $abstractProductIds, $idProductRelation)
    {
        foreach ($abstractProductIds as $index => $id) {
            $productRelationProductAbstractEntity = $this->createProductRelationProductAbstractEntity();
            $productRelationProductAbstractEntity->setFkProductRelation($idProductRelation);
            $productRelationProductAbstractEntity->setFkProductAbstract($id);
            $productRelationProductAbstractEntity->setOrder($index + 1);
            $productRelationProductAbstractEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTypeTransfer $productRelationTypeTransfer
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationType
     */
    protected function findOrCreateProductRelationTypeEntity(ProductRelationTypeTransfer $productRelationTypeTransfer)
    {
        $productRelationTypeEntity = $this->productRelationQueryContainer
            ->queryProductRelationTypeByKey($productRelationTypeTransfer->getKey())
            ->findOneOrCreate();

        return $productRelationTypeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelationType $productRelationTypeEntity
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation
     */
    protected function mapProductRelationEntity(
        ProductRelationTransfer $productRelationTransfer,
        SpyProductRelationType $productRelationTypeEntity
    ) {
        $productRelationEntity = $this->createProductRelationEntity();
        $productRelationEntity->fromArray($productRelationTransfer->toArray());
        $productRelationEntity->setQuerySetData($this->encodeQuerySet($productRelationTransfer));
        $productRelationEntity->setFkProductRelationType($productRelationTypeEntity->getIdProductRelationType());

        return $productRelationEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation
     */
    protected function updateProductRelationEntity(
        ProductRelationTransfer $productRelationTransfer,
        SpyProductRelation $productRelationEntity
    ) {
        $productRelationEntity->fromArray($productRelationTransfer->modifiedToArray());
        $productRelationEntity->setQuerySetData($this->encodeQuerySet($productRelationTransfer));
        if ($productRelationTransfer->getProductRelationType()) {
            $productRelationEntity->setFkProductRelationType(
                $productRelationTransfer->getProductRelationType()->getIdProductRelationType()
            );
        }

        return $productRelationEntity;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchRelationActive($idProductAbstract)
    {
        $this->touchFacade->touchActive(
            ProductRelationConstants::RESOURCE_TYPE_PRODUCT_RELATION,
            $idProductAbstract
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    protected function assertUpdateRelation(ProductRelationTransfer $productRelationTransfer)
    {
        $productRelationTransfer->requireIdProductRelation()
            ->requireFkProductAbstract();
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation|null
     */
    protected function findProductRelationEntityById($idProductRelation)
    {
        $productRelationEntity = $this->productRelationQueryContainer
            ->queryProductRelationByIdProductRelation($idProductRelation)
            ->findOne();

        return $productRelationEntity;
    }

    /**
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract
     */
    protected function createProductRelationProductAbstractEntity()
    {
        return new SpyProductRelationProductAbstract();
    }

    /**
     * @param int $idProductRelation
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract
     */
    protected function findOrCreateProductRelationProductByIdAndIdProduct($idProductRelation, $idProductAbstract)
    {
        return $this->productRelationQueryContainer
            ->queryProductRelationProductAbstractByIdRelationAndIdProduct($idProductRelation, $idProductAbstract)
            ->findOneOrCreate();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    protected function findRuleMatchingProducts(ProductRelationTransfer $productRelationTransfer)
    {
        return $this->productRelationQueryContainer
            ->getRulePropelQuery($productRelationTransfer)
            ->find();
    }

    /**
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation
     */
    protected function createProductRelationEntity()
    {
        return new SpyProductRelation();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return string
     */
    protected function encodeQuerySet(ProductRelationTransfer $productRelationTransfer)
    {
        return $this->utilEncodingService->encodeJson(
            $productRelationTransfer
                ->getQuerySet()
                ->toArray(true)
        );
    }
}
