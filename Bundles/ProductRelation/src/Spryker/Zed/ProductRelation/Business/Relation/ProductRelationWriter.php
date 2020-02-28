<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generator;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelation;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationType;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException;
use Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;
use Spryker\Zed\ProductRelation\ProductRelationConfig;

class ProductRelationWriter implements ProductRelationWriterInterface
{
    use TransactionTrait;

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
     * @var \Spryker\Zed\ProductRelation\ProductRelationConfig
     */
    protected $productRelationConfig;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface
     */
    protected $relatedProductReader;

    /**
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\ProductRelation\ProductRelationConfig $productRelationConfig
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface $relatedProductReader
     */
    public function __construct(
        ProductRelationToTouchInterface $touchFacade,
        ProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationToUtilEncodingInterface $utilEncodingService,
        ProductRelationConfig $productRelationConfig,
        RelatedProductReaderInterface $relatedProductReader
    ) {
        $this->touchFacade = $touchFacade;
        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->productRelationConfig = $productRelationConfig;
        $this->relatedProductReader = $relatedProductReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int
     */
    public function saveRelation(ProductRelationTransfer $productRelationTransfer)
    {
        $productRelationTransfer->requireProductRelationType();

        return $this->getTransactionHandler()->handleTransaction(function () use ($productRelationTransfer) {
            return $this->executeSaveRelationTransaction($productRelationTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int
     */
    protected function executeSaveRelationTransaction(
        ProductRelationTransfer $productRelationTransfer
    ) {
        $productRelationTypeTransfer = $productRelationTransfer->getProductRelationType();
        $productRelationTypeEntity = $this->findOrCreateProductRelationTypeEntity($productRelationTypeTransfer);
        $productRelationTypeEntity->save();

        $productRelationEntity = $this->mapProductRelationEntity($productRelationTransfer, $productRelationTypeEntity);
        $productRelationEntity->save();
        $productRelationTransfer->setIdProductRelation($productRelationEntity->getIdProductRelation());

        $this->saveAllRelatedProducts($productRelationTransfer);
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

        $this->getTransactionHandler()->handleTransaction(function () use ($productRelationTransfer, $productRelationEntity) {
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
        $productRelationTransfer->setIdProductRelation($productRelationEntity->getIdProductRelation());

        $this->removeRelatedProductsByIdRelation($productRelationTransfer->getIdProductRelation());

        $this->saveAllRelatedProducts($productRelationTransfer);

        $this->touchRelationActive($productRelationTransfer->getFkProductAbstract());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    protected function saveAllRelatedProducts(ProductRelationTransfer $productRelationTransfer): void
    {
        foreach ($this->relatedProductReader->findMatchingProducts($productRelationTransfer) as $relatedProductTransfers) {
            $productAbstractIds = [];

            foreach ($relatedProductTransfers as $relatedProductTransfer) {
                $productAbstractIds[] = $relatedProductTransfer->getIdProductAbstract();
            }

            $this->saveRelatedProducts($productAbstractIds, $productRelationTransfer->getIdProductRelation());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generator|\Orm\Zed\Product\Persistence\SpyProductAbstract[][]
     */
    protected function findMatchingProducts(ProductRelationTransfer $productRelationTransfer): Generator
    {
        $count = $this->productRelationQueryContainer
            ->getRulePropelQuery($productRelationTransfer)
            ->count();

        $limit = $this->productRelationConfig->getProductRelationUpdateChunkSize();

        for ($offset = 0; $offset <= $count; $offset += $limit) {
            yield $this->productRelationQueryContainer
                ->getRulePropelQuery($productRelationTransfer)
                ->limit($limit)
                ->offset($offset)
                ->find();
        }
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

        $deleted = $this->getTransactionHandler()->handleTransaction(function () use ($productRelationEntity) {
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
