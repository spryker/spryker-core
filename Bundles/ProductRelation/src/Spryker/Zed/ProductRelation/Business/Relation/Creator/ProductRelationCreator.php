<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Creator;

use Generated\Shared\Transfer\ProductRelationResponseTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationStoreRelationUpdaterInterface;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\RelatedProductUpdaterInterface;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface;

class ProductRelationCreator implements ProductRelationCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface
     */
    protected $productRelationEntityManager;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Updater\RelatedProductUpdaterInterface
     */
    protected $relatedProductUpdater;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationStoreRelationUpdaterInterface
     */
    protected $productRelationStoreRelationUpdater;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface $productRelationEntityManager
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Updater\RelatedProductUpdaterInterface $relatedProductUpdater
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationStoreRelationUpdaterInterface $productRelationStoreRelationUpdater
     */
    public function __construct(
        ProductRelationEntityManagerInterface $productRelationEntityManager,
        ProductRelationToTouchInterface $touchFacade,
        RelatedProductUpdaterInterface $relatedProductUpdater,
        ProductRelationStoreRelationUpdaterInterface $productRelationStoreRelationUpdater
    ) {
        $this->productRelationEntityManager = $productRelationEntityManager;
        $this->touchFacade = $touchFacade;
        $this->relatedProductUpdater = $relatedProductUpdater;
        $this->productRelationStoreRelationUpdater = $productRelationStoreRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function createProductRelation(ProductRelationTransfer $productRelationTransfer): ProductRelationResponseTransfer
    {
        $productRelationTransfer->requireProductRelationType()
            ->requireProductRelationKey();

        return $this->getTransactionHandler()->handleTransaction(function () use ($productRelationTransfer) {
            return $this->executeCreateProductRelationTransaction($productRelationTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    protected function executeCreateProductRelationTransaction(
        ProductRelationTransfer $productRelationTransfer
    ): ProductRelationResponseTransfer {
        $productRelationResponseTransfer = $this->createProductRelationResponseTransfer();
        $storeRelationTransfer = $productRelationTransfer->getStoreRelation();
        $productRelationTransfer = $this->createProductRelationWithType($productRelationTransfer);

        $storeRelationTransfer->setIdEntity($productRelationTransfer->getIdProductRelation());

        $this->updateAllRelatedProducts($productRelationTransfer);
        $this->updateStoreRelations($storeRelationTransfer);

        $this->touchRelationActive($productRelationTransfer->getFkProductAbstract());

        return $productRelationResponseTransfer->setIsSuccessful(true)
            ->setProductRelation($productRelationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    protected function updateAllRelatedProducts(
        ProductRelationTransfer $productRelationTransfer
    ): void {
        $this->relatedProductUpdater->updateAllRelatedProducts($productRelationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    protected function updateStoreRelations(StoreRelationTransfer $storeRelationTransfer): void
    {
        $this->productRelationStoreRelationUpdater->update($storeRelationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function createProductRelationWithType(
        ProductRelationTransfer $productRelationTransfer
    ): ProductRelationTransfer {
        $productRelationTypeTransfer = $this->productRelationEntityManager
            ->saveProductRelationType($productRelationTransfer->getProductRelationType());
        $productRelationTransfer->setProductRelationType($productRelationTypeTransfer);

        return $this->productRelationEntityManager
            ->createProductRelation($productRelationTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    protected function createProductRelationResponseTransfer(): ProductRelationResponseTransfer
    {
        return (new ProductRelationResponseTransfer())
            ->setIsSuccessful(false);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchRelationActive(int $idProductAbstract): void
    {
        $this->touchFacade->touchActive(
            ProductRelationConstants::RESOURCE_TYPE_PRODUCT_RELATION,
            $idProductAbstract
        );
    }
}
