<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued;

use Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface;

class ProductDiscontinuedDeleter implements ProductDiscontinuedDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface
     */
    protected $productDiscontinuedEntityManager;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig
     */
    protected $productDiscontinuedConfig;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface
     */
    protected $productDiscontinuedRepository;

    /**
     * @var array
     */
    protected $postDeleteProductDiscontinuePlugins;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface $productDiscontinuedEntityManager
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository
     * @param \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostProductDiscontinuePluginInterface[] $postDeleteProductDiscontinuePlugins
     */
    public function __construct(
        ProductDiscontinuedEntityManagerInterface $productDiscontinuedEntityManager,
        ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository,
        array $postDeleteProductDiscontinuePlugins
    ) {
        $this->productDiscontinuedEntityManager = $productDiscontinuedEntityManager;
        $this->productDiscontinuedRepository = $productDiscontinuedRepository;
        $this->postDeleteProductDiscontinuePlugins = $postDeleteProductDiscontinuePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function delete(ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer): ProductDiscontinuedResponseTransfer
    {
        $productDiscontinuedTransfer = (new ProductDiscontinuedTransfer())
            ->setFkProduct($productDiscontinuedRequestTransfer->getIdProduct());
        $productDiscontinuedTransfer = $this->productDiscontinuedRepository->findProductDiscontinuedByProductId($productDiscontinuedTransfer);
        if (!$productDiscontinuedTransfer) {
            return (new ProductDiscontinuedResponseTransfer)->setIsSuccessful(false);
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($productDiscontinuedTransfer) {
            return $this->executeDeleteTransaction($productDiscontinuedTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    protected function executeDeleteTransaction(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer
    ): ProductDiscontinuedResponseTransfer {
        $this->productDiscontinuedEntityManager->deleteProductDiscontinuedNotes($productDiscontinuedTransfer);
        $this->productDiscontinuedEntityManager->deleteProductDiscontinued($productDiscontinuedTransfer);
        $this->executePostDeletePlugins($productDiscontinuedTransfer);

        return (new ProductDiscontinuedResponseTransfer)->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    protected function executePostDeletePlugins(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void
    {
        foreach ($this->postDeleteProductDiscontinuePlugins as $postDeleteProductDiscontinuePlugin) {
            $postDeleteProductDiscontinuePlugin->execute($productDiscontinuedTransfer);
        }
    }
}
