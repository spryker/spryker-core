<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedDeactivator;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedPluginExecutorInterface;
use Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeInterface;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface;

class ProductDiscontinuedDeactivator implements ProductDiscontinuedDeactivatorInterface
{
    use TransactionTrait;

    protected const DEACTIVATE_BATCH_SIZE = 1000;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface
     */
    protected $productDiscontinuedRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    protected $logger;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface
     */
    protected $productDiscontinuedEntityManager;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedPluginExecutorInterface
     */
    protected $productDiscontinuedPluginExecutor;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface $productDiscontinuedEntityManager
     * @param \Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedPluginExecutorInterface $productDiscontinuedPluginExecutor
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(
        ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository,
        ProductDiscontinuedEntityManagerInterface $productDiscontinuedEntityManager,
        ProductDiscontinuedToProductFacadeInterface $productFacade,
        ProductDiscontinuedPluginExecutorInterface $productDiscontinuedPluginExecutor,
        ?LoggerInterface $logger = null
    ) {
        $this->productDiscontinuedRepository = $productDiscontinuedRepository;
        $this->productFacade = $productFacade;
        $this->logger = $logger;
        $this->productDiscontinuedEntityManager = $productDiscontinuedEntityManager;
        $this->productDiscontinuedPluginExecutor = $productDiscontinuedPluginExecutor;
    }

    /**
     * @return void
     */
    public function deactivate(): void
    {
        do {
            $productDiscontinuedCollectionTransfer = $this->productDiscontinuedRepository
                ->findProductsToDeactivate(static::DEACTIVATE_BATCH_SIZE);

            $this->addStartMessage($productDiscontinuedCollectionTransfer->getDiscontinuedProducts()->count());

            $this->getTransactionHandler()->handleTransaction(function () use ($productDiscontinuedCollectionTransfer) {
                $this->executeDeactivateTransaction($productDiscontinuedCollectionTransfer);
            });
        } while ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts()->count() > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return void
     */
    protected function executeDeactivateTransaction(ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer): void
    {
        $productConcreteSkus = $this->getProductConcreteSkusFromProductDiscontinuedCollection($productDiscontinuedCollectionTransfer);
        $productDiscontinuedIds = $this->getProductDiscontinuedIdsFromProductDiscontinuedCollection($productDiscontinuedCollectionTransfer);

        $this->productFacade->deactivateProductConcretesByConcreteSkus($productConcreteSkus);
        $this->productDiscontinuedEntityManager->deleteProductDiscontinuedNotesInBulk($productDiscontinuedIds);
        $this->productDiscontinuedEntityManager->deleteProductDiscontinuedInBulk($productDiscontinuedIds);
        $this->productDiscontinuedPluginExecutor->executeBulkPostDeleteProductDiscontinuedPlugins($productDiscontinuedCollectionTransfer);
        $this->addProductDeactivatedMessages($productDiscontinuedCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return string[]
     */
    protected function getProductConcreteSkusFromProductDiscontinuedCollection(
        ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
    ): array {
        $productConcreteSkus = [];

        foreach ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts() as $productDiscontinuedTransfer) {
            $productConcreteSkus[] = $productDiscontinuedTransfer->getSku();
        }

        return $productConcreteSkus;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return int[]
     */
    protected function getProductDiscontinuedIdsFromProductDiscontinuedCollection(
        ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
    ): array {
        $productDiscontinuedIds = [];

        foreach ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts() as $productDiscontinuedTransfer) {
            $productDiscontinuedIds[] = $productDiscontinuedTransfer->getIdProductDiscontinued();
        }

        return array_unique($productDiscontinuedIds);
    }

    /**
     * @param int $productNumber
     *
     * @return void
     */
    protected function addStartMessage(int $productNumber): void
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->debug(
            sprintf(
                'Found %d products to deactivate.',
                $productNumber
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return void
     */
    protected function addProductDeactivatedMessages(ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer): void
    {
        if (!$this->logger) {
            return;
        }

        foreach ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts() as $productDiscontinuedTransfer) {
            $this->logger->info(
                sprintf(
                    'Product %d was deactivated.',
                    $productDiscontinuedTransfer->getFkProduct()
                )
            );
        }
    }
}
