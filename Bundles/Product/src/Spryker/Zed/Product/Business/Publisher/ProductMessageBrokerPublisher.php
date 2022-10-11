<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Publisher;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\ProductCreatedTransfer;
use Generated\Shared\Transfer\ProductDeletedTransfer;
use Generated\Shared\Transfer\ProductExportedTransfer;
use Generated\Shared\Transfer\ProductPublisherConfigTransfer;
use Generated\Shared\Transfer\ProductUpdatedTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Product\Business\Exception\ProductPublisherEventNameMismatchException;
use Spryker\Zed\Product\Business\Exception\ProductPublisherWrongChunkSizeException;
use Spryker\Zed\Product\Business\Reader\ProductConcreteReaderInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterfrace;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;
use Spryker\Zed\Product\ProductConfig;
use Throwable;

class ProductMessageBrokerPublisher implements ProductPublisherInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\Product\Business\Reader\ProductConcreteReaderInterface
     */
    protected $productConcreteReader;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterfrace
     */
    protected $messageBrokerFacade;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Spryker\Zed\Product\ProductConfig
     */
    protected $productConfig;

    /**
     * @param \Spryker\Zed\Product\Business\Reader\ProductConcreteReaderInterface $productConcreteReader
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterfrace $messageBrokerFacade
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     * @param \Spryker\Zed\Product\ProductConfig $productConfig
     */
    public function __construct(
        ProductConcreteReaderInterface $productConcreteReader,
        ProductToMessageBrokerInterfrace $messageBrokerFacade,
        ProductRepositoryInterface $productRepository,
        ProductConfig $productConfig
    ) {
        $this->productConcreteReader = $productConcreteReader;
        $this->messageBrokerFacade = $messageBrokerFacade;
        $this->productRepository = $productRepository;
        $this->productConfig = $productConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @return void
     */
    public function publish(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void
    {
        $this->assertEventNameIsProperForPublish($productPublisherConfigTransfer);

        $this->performProductsConcretePublish($productPublisherConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @return void
     */
    public function unpublish(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void
    {
        $this->assertEventNameIsProperForUnpublish($productPublisherConfigTransfer);

        $this->performProductsConcreteUnpublish($productPublisherConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductPublisherEventNameMismatchException
     *
     * @return void
     */
    protected function assertEventNameIsProperForPublish(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void
    {
        if (
            !in_array(
                $productPublisherConfigTransfer->getEventName(),
                [
                    ProductExportedTransfer::class,
                    ProductCreatedTransfer::class,
                    ProductUpdatedTransfer::class,
                ],
                true,
            )
        ) {
            throw new ProductPublisherEventNameMismatchException();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductPublisherEventNameMismatchException
     *
     * @return void
     */
    protected function assertEventNameIsProperForUnpublish(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void
    {
        if ($productPublisherConfigTransfer->getEventName() !== ProductDeletedTransfer::class) {
            throw new ProductPublisherEventNameMismatchException();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductPublisherWrongChunkSizeException
     *
     * @return void
     */
    protected function performProductsConcretePublish(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void
    {
        if ($this->productConfig->getProductPublishToMessageBrokerChunkSize() < 1) {
            throw new ProductPublisherWrongChunkSizeException('Chunk size must be greater than 0');
        }

        $productConcreteIdsChunks = array_chunk(
            $this->getProductConcreteIds($productPublisherConfigTransfer),
            $this->productConfig->getProductPublishToMessageBrokerChunkSize(),
        );

        foreach ($productConcreteIdsChunks as $productConcreteIdsChunk) {
            $productsConcrete = $this->readProductsConcreteByIdsIndexedByStoreReference($productConcreteIdsChunk);

            if (!count($productsConcrete)) {
                continue;
            }

            foreach ($productsConcrete as $storeReference => $productConcreteTransfers) {
                $publishTransfer = $this->createPublishTransfer(
                    $productPublisherConfigTransfer->getEventName(),
                    $productConcreteTransfers,
                    $storeReference,
                );

                $this->messageBrokerFacade->sendMessage($publishTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @return void
     */
    protected function performProductsConcreteUnpublish(ProductPublisherConfigTransfer $productPublisherConfigTransfer)
    {
        $productConcreteIds = $this->getProductConcreteIds($productPublisherConfigTransfer);

        $productConcreteTransfers = $this->productRepository
            ->getProductConcreteTransfersByProductIds($productConcreteIds);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productConcreteTransfer->getStores() as $storeReference => $storeTransfer) {
                $messageAttributesTransfer = (new MessageAttributesTransfer())
                    ->setStoreReference($storeReference);

                $productDeletedTransfer = (new ProductDeletedTransfer())
                    ->setSku($productConcreteTransfer->getSku())
                    ->setMessageAttributes($messageAttributesTransfer);

                $this->messageBrokerFacade->sendMessage($productDeletedTransfer);
            }
        }
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<string, array<\Generated\Shared\Transfer\ProductConcreteTransfer>>
     */
    protected function readProductsConcreteByIdsIndexedByStoreReference(array $productConcreteIds): array
    {
        $productsConcrete = [];

        foreach ($productConcreteIds as $productConcreteId) {
            try {
                $productConcreteTransfer = $this->productConcreteReader
                    ->readProductConcreteMergedWithProductAbstractById($productConcreteId);

                foreach ($productConcreteTransfer->getStores() as $storeTransfer) {
                    if (!$storeTransfer->getStoreReference()) {
                        continue;
                    }

                    $productsConcrete[$storeTransfer->getStoreReference()][] = $productConcreteTransfer;
                }
            } catch (Throwable $throwable) {
                $this->getLogger()->error('Read product error: ' . $throwable->getMessage(), $throwable->getTrace());
            }
        }

        return $productsConcrete;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @return array<int>
     */
    protected function getProductConcreteIds(ProductPublisherConfigTransfer $productPublisherConfigTransfer): array
    {
        $productConcreteIds = $productPublisherConfigTransfer->getProductIds();

        $productConcreteIds = array_merge(
            $productConcreteIds,
            $this->productRepository->findProductConcreteIdsByProductAbstractIds(
                $productPublisherConfigTransfer->getProductAbstractIds(),
            ),
        );

        return array_unique($productConcreteIds);
    }

    /**
     * @param string $publishTransferClass
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productsConcrete
     * @param string $storeReference
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createPublishTransfer(
        string $publishTransferClass,
        array $productsConcrete,
        string $storeReference
    ): TransferInterface {
        $messageAttributesTransfer = (new MessageAttributesTransfer())->setStoreReference($storeReference);

        /** @var \Generated\Shared\Transfer\ProductExportedTransfer|\Generated\Shared\Transfer\ProductCreatedTransfer|\Generated\Shared\Transfer\ProductUpdatedTransfer $publishTransfer */
        $publishTransfer = new $publishTransferClass();
        $publishTransfer->setMessageAttributes($messageAttributesTransfer);

        foreach ($productsConcrete as $productConcrete) {
            $publishTransfer->addProductConcrete($productConcrete);
        }

        return $publishTransfer;
    }
}
