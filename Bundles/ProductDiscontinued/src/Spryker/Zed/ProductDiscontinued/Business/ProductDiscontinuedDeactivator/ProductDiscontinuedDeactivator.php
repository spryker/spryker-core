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
        $productDiscontinuedCollectionTransfer = $this->productDiscontinuedRepository->findProductsToDeactivate();
        if (!$productDiscontinuedCollectionTransfer->getDiscontinuedProducts()->count()) {
            return;
        }

        $this->addStartMessage($productDiscontinuedCollectionTransfer->getDiscontinuedProducts()->count());

        $this->getTransactionHandler()->handleTransaction(function () use ($productDiscontinuedCollectionTransfer) {
            $this->executeDeactivateTransaction($productDiscontinuedCollectionTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return void
     */
    protected function executeDeactivateTransaction(ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer): void
    {
        foreach ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts() as $productDiscontinuedTransfer) {
            $this->productFacade->deactivateProductConcrete($productDiscontinuedTransfer->getFkProduct());
            $this->productDiscontinuedEntityManager->deleteProductDiscontinuedNotes($productDiscontinuedTransfer);
            $this->productDiscontinuedEntityManager->deleteProductDiscontinued($productDiscontinuedTransfer);
            $this->productDiscontinuedPluginExecutor->executePostDeleteProductDiscontinuedPlugins($productDiscontinuedTransfer);
            $this->addProductDeactivatedMessage($productDiscontinuedTransfer->getFkProduct());
        }
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
     * @param int $idProduct
     *
     * @return void
     */
    protected function addProductDeactivatedMessage(int $idProduct): void
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->info(
            sprintf(
                'Product %d was deactivated.',
                $idProduct
            )
        );
    }
}
