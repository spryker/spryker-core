<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Deleter;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductRelationResponseTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface;

class ProductRelationDeleter implements ProductRelationDeleterInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_PRODUCT_RELATION_NOT_FOUND = 'Product relation #%d not found';

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface
     */
    protected $productRelationRepository;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface
     */
    protected $productRelationEntityManager;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface $productRelationRepository
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface $productRelationEntityManager
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface $touchFacade
     */
    public function __construct(
        ProductRelationRepositoryInterface $productRelationRepository,
        ProductRelationEntityManagerInterface $productRelationEntityManager,
        ProductRelationToTouchInterface $touchFacade
    ) {
        $this->productRelationRepository = $productRelationRepository;
        $this->productRelationEntityManager = $productRelationEntityManager;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function deleteProductRelation(int $idProductRelation): ProductRelationResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($idProductRelation) {
            return $this->executeDeleteProductRelationTransaction($idProductRelation);
        });
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    protected function executeDeleteProductRelationTransaction(int $idProductRelation): ProductRelationResponseTransfer
    {
        $productRelationResponseTransfer = $this->createProductRelationResponseTransfer();
        $productRelationTransfer = $this->productRelationRepository->findProductRelationById($idProductRelation);

        if (!$productRelationTransfer) {
            return $productRelationResponseTransfer->addMessage(
                $this->getErrorMessageTransfer(sprintf(static::ERROR_MESSAGE_PRODUCT_RELATION_NOT_FOUND, $idProductRelation))
            );
        }

        $this->removeRelatedProducts($idProductRelation);
        $this->removeStoreRelations($idProductRelation);
        $this->touchRelationDeleted($productRelationTransfer);
        $this->productRelationEntityManager->deleteProductRelationById($idProductRelation);

        return $productRelationResponseTransfer->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    protected function touchRelationDeleted(ProductRelationTransfer $productRelationTransfer): void
    {
        $this->touchFacade->touchDeleted(
            ProductRelationConstants::RESOURCE_TYPE_PRODUCT_RELATION,
            $productRelationTransfer->getFkProductAbstract()
        );
    }

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    protected function removeRelatedProducts(int $idProductRelation): void
    {
        $this->productRelationEntityManager
            ->removeRelatedProductsByIdProductRelation($idProductRelation);
    }

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    protected function removeStoreRelations(int $idProductRelation): void
    {
        $this->productRelationEntityManager
            ->deleteProductRelationStoresByIdProductRelation($idProductRelation);
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
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getErrorMessageTransfer(string $message): MessageTransfer
    {
        return (new MessageTransfer())->setValue($message);
    }
}
