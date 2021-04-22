<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferErrorTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOffer\Business\Generator\ProductOfferReferenceGeneratorInterface;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;
use Spryker\Zed\ProductOffer\ProductOfferConfig;

class ProductOfferWriter implements ProductOfferWriterInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_PRODUCT_OFFER_NOT_FOUND = 'Product offer is not found.';

    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface
     */
    protected $productOfferRepository;

    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface
     */
    protected $productOfferEntityManager;

    /**
     * @var \Spryker\Zed\ProductOffer\Business\Generator\ProductOfferReferenceGeneratorInterface
     */
    protected $productOfferReferenceGenerator;

    /**
     * @var \Spryker\Zed\ProductOffer\ProductOfferConfig
     */
    protected $productOfferConfig;

    /**
     * @var array|\Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostCreatePluginInterface[]
     */
    protected $productOfferPostCreatePlugins;

    /**
     * @var array|\Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostUpdatePluginInterface[]
     */
    protected $productOfferPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface $productOfferRepository
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface $productOfferEntityManager
     * @param \Spryker\Zed\ProductOffer\Business\Generator\ProductOfferReferenceGeneratorInterface $productOfferReferenceGenerator
     * @param \Spryker\Zed\ProductOffer\ProductOfferConfig $productOfferConfig
     * @param \Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostCreatePluginInterface[] $productOfferPostCreatePlugins
     * @param \Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostUpdatePluginInterface[] $productOfferPostUpdatePlugins
     */
    public function __construct(
        ProductOfferRepositoryInterface $productOfferRepository,
        ProductOfferEntityManagerInterface $productOfferEntityManager,
        ProductOfferReferenceGeneratorInterface $productOfferReferenceGenerator,
        ProductOfferConfig $productOfferConfig,
        array $productOfferPostCreatePlugins = [],
        array $productOfferPostUpdatePlugins = []
    ) {
        $this->productOfferRepository = $productOfferRepository;
        $this->productOfferEntityManager = $productOfferEntityManager;
        $this->productOfferReferenceGenerator = $productOfferReferenceGenerator;
        $this->productOfferConfig = $productOfferConfig;
        $this->productOfferPostCreatePlugins = $productOfferPostCreatePlugins;
        $this->productOfferPostUpdatePlugins = $productOfferPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function create(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferTransfer = $this->setDefaultValues($productOfferTransfer);

        return $this->getTransactionHandler()->handleTransaction(function () use ($productOfferTransfer) {
            return $this->executeCreateTransaction($productOfferTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferResponseTransfer
     */
    public function update(ProductOfferTransfer $productOfferTransfer): ProductOfferResponseTransfer
    {
        if (
            !$productOfferTransfer->getIdProductOffer()
            || !$this->productOfferRepository->findOne((new ProductOfferCriteriaTransfer())->setIdProductOffer($productOfferTransfer->getIdProductOffer()))
        ) {
            return (new ProductOfferResponseTransfer())
                ->setIsSuccessful(false)
                ->addError(
                    (new ProductOfferErrorTransfer())->setMessage(static::ERROR_MESSAGE_PRODUCT_OFFER_NOT_FOUND)
                );
        }

        $productOfferTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($productOfferTransfer) {
            return $this->executeUpdateTransaction($productOfferTransfer);
        });

        return (new ProductOfferResponseTransfer())
            ->setIsSuccessful(true)
            ->setProductOffer($productOfferTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function executeCreateTransaction(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferTransfer = $this->productOfferEntityManager->createProductOffer($productOfferTransfer);
        $productOfferTransfer = $this->productOfferEntityManager->createProductOfferStores($productOfferTransfer);
        $productOfferTransfer = $this->executeProductOfferPostCreatePlugins($productOfferTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function executeUpdateTransaction(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferTransfer = $this->productOfferEntityManager->updateProductOffer($productOfferTransfer);
        $productOfferTransfer = $this->updateProductOfferStores($productOfferTransfer);
        $productOfferTransfer = $this->executeProductOfferPostUpdatePlugins($productOfferTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function updateProductOfferStores(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        if (!$productOfferTransfer->isPropertyModified(ProductOfferTransfer::STORES)) {
            return $productOfferTransfer;
        }

        $productOfferTransfer->requireIdProductOffer();

        $persistedProductOfferStoreTransfers = $this->indexStoreTransfersByIdStore(
            $this->productOfferRepository->getProductOfferStores($productOfferTransfer->getIdProductOffer())
        );
        $productOfferStoreTransfers = $this->indexStoreTransfersByIdStore($productOfferTransfer->getStores()->getArrayCopy());

        $this->productOfferEntityManager->deleteProductOfferStores(
            $productOfferTransfer->getIdProductOffer(),
            array_keys(array_diff_key($persistedProductOfferStoreTransfers, $productOfferStoreTransfers))
        );

        $this->productOfferEntityManager->createProductOfferStores(
            (new ProductOfferTransfer())
                ->setStores(new ArrayObject(
                    array_diff_key($productOfferStoreTransfers, $persistedProductOfferStoreTransfers)
                ))
                ->setIdProductOffer($productOfferTransfer->getIdProductOffer())
        );

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function executeProductOfferPostCreatePlugins(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        foreach ($this->productOfferPostCreatePlugins as $productOfferPostCreatePlugin) {
            $productOfferTransfer = $productOfferPostCreatePlugin->execute($productOfferTransfer);
        }

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function executeProductOfferPostUpdatePlugins(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        foreach ($this->productOfferPostUpdatePlugins as $productOfferPostUpdatePlugin) {
            $productOfferTransfer = $productOfferPostUpdatePlugin->execute($productOfferTransfer);
        }

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function setDefaultValues(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        if ($productOfferTransfer->getProductOfferReference() === null) {
            $productOfferTransfer->setProductOfferReference($this->productOfferReferenceGenerator->generateProductOfferReference());
        }

        if ($productOfferTransfer->getApprovalStatus() === null) {
            $productOfferTransfer->setApprovalStatus($this->productOfferConfig->getDefaultApprovalStatus());
        }

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function indexStoreTransfersByIdStore(array $storeTransfers): array
    {
        $indexedStoreTransfers = [];
        foreach ($storeTransfers as $storeTransfer) {
            $indexedStoreTransfers[$storeTransfer->getIdStore()] = $storeTransfer;
        }

        return $indexedStoreTransfers;
    }
}
