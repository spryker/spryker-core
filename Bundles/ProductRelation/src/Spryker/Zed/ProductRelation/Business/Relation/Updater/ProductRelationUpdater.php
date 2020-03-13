<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Updater;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductRelationResponseTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface;

class ProductRelationUpdater implements ProductRelationUpdaterInterface
{
    use TransactionTrait;

    protected const MESSAGE_UPDATE_ERROR = 'It is impossible to update product relation #%d';

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface
     */
    protected $productRelationEntityManager;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Updater\RelatedProductUpdaterInterface
     */
    protected $relatedProductsUpdater;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationStoreRelationUpdaterInterface
     */
    protected $productRelationStoreRelationUpdater;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface $productRelationEntityManager
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Updater\RelatedProductUpdaterInterface $relatedProductsUpdater
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationStoreRelationUpdaterInterface $productRelationStoreRelationUpdater
     */
    public function __construct(
        ProductRelationEntityManagerInterface $productRelationEntityManager,
        RelatedProductUpdaterInterface $relatedProductsUpdater,
        ProductRelationToTouchInterface $touchFacade,
        ProductRelationStoreRelationUpdaterInterface $productRelationStoreRelationUpdater
    ) {
        $this->productRelationEntityManager = $productRelationEntityManager;
        $this->relatedProductsUpdater = $relatedProductsUpdater;
        $this->touchFacade = $touchFacade;
        $this->productRelationStoreRelationUpdater = $productRelationStoreRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function updateProductRelation(ProductRelationTransfer $productRelationTransfer): ProductRelationResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productRelationTransfer) {
            return $this->executeUpdateProductRelationTransaction($productRelationTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    protected function executeUpdateProductRelationTransaction(
        ProductRelationTransfer $productRelationTransfer
    ): ProductRelationResponseTransfer {
        $this->assertUpdateRelation($productRelationTransfer);
        $idProductRelation = $productRelationTransfer->getIdProductRelation();
        $storeRelationTransfer = $productRelationTransfer->getStoreRelation()
            ->setIdEntity($productRelationTransfer->getIdProductRelation());
        $productRelationResponseTransfer = $this->createProductRelationResponseTransfer();

        $productRelationTransfer = $this->updateProductRelationWithRelationType($productRelationTransfer);

        if (!$productRelationTransfer) {
            return $productRelationResponseTransfer
                ->addMessage($this->createErrorMessageTransfer(sprintf(static::MESSAGE_UPDATE_ERROR, $idProductRelation)));
        }

        $this->removeRelatedProducts($productRelationTransfer->getIdProductRelation());
        $this->updateRelatedProducts($productRelationTransfer);
        $this->updateStoreRelation($storeRelationTransfer);

        $this->touchRelationActive($productRelationTransfer->getFkProductAbstract());

        return $productRelationResponseTransfer
            ->setIsSuccessful(true)
            ->setProductRelation($productRelationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    protected function updateStoreRelation(StoreRelationTransfer $storeRelationTransfer): void
    {
        $this->productRelationStoreRelationUpdater->update($storeRelationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    protected function updateRelatedProducts(ProductRelationTransfer $productRelationTransfer): void
    {
        $this->relatedProductsUpdater->updateAllRelatedProducts($productRelationTransfer);
    }

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    protected function removeRelatedProducts(int $idProductRelation): void
    {
        $this->productRelationEntityManager->removeRelatedProductsByIdProductRelation(
            $idProductRelation
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    protected function updateProductRelationWithRelationType(
        ProductRelationTransfer $productRelationTransfer
    ): ?ProductRelationTransfer {
        $productRelationTypeTransfer = $this->productRelationEntityManager->saveProductRelationType(
            $productRelationTransfer->getProductRelationType()
        );
        $productRelationTransfer->setProductRelationType($productRelationTypeTransfer);

        return $this->productRelationEntityManager
            ->updateProductRelation($productRelationTransfer);
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
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    protected function assertUpdateRelation(ProductRelationTransfer $productRelationTransfer): void
    {
        $productRelationTransfer->requireIdProductRelation()
            ->requireFkProductAbstract();
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createErrorMessageTransfer(string $message): MessageTransfer
    {
        return (new MessageTransfer())->setValue($message);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchRelationActive($idProductAbstract): void
    {
        $this->touchFacade->touchActive(
            ProductRelationConstants::RESOURCE_TYPE_PRODUCT_RELATION,
            $idProductAbstract
        );
    }
}
