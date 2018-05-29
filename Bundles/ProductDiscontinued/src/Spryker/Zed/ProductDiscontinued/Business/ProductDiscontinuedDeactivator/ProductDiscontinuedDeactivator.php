<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedDeactivator;

use Psr\Log\LoggerInterface;
use Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeInterface;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface;

class ProductDiscontinuedDeactivator implements ProductDiscontinuedDeactivatorInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface
     */
    protected $productDiscontinuedRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var null|\Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * ProductDiscontinuedDeactivator constructor.
     *
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository
     * @param \Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeInterface $productFacade
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(
        ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository,
        ProductDiscontinuedToProductFacadeInterface $productFacade,
        ?LoggerInterface $logger = null
    ) {
        $this->productDiscontinuedRepository = $productDiscontinuedRepository;
        $this->productFacade = $productFacade;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function deactivate(): void
    {
        $productDiscontinuedCollectionTransfer = $this->productDiscontinuedRepository->findProductsToDiactivate();
        if (!$productDiscontinuedCollectionTransfer->getProductDiscontinueds()->count()) {
            return;
        }

        $this->addStartMessage($productDiscontinuedCollectionTransfer->getProductDiscontinueds()->count());
        foreach ($productDiscontinuedCollectionTransfer->getProductDiscontinueds() as $productDiscontinuedTransfer) {
            $productDiscontinuedTransfer->getFkProduct();
            $this->productFacade->deactivateProductConcrete($productDiscontinuedTransfer->getFkProduct());
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
