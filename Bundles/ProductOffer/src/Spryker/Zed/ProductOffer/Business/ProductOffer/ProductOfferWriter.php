<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\ProductOffer;

use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferErrorTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class ProductOfferWriter implements ProductOfferWriterInterface
{
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
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface $productOfferRepository
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface $productOfferEntityManager
     */
    public function __construct(
        ProductOfferRepositoryInterface $productOfferRepository,
        ProductOfferEntityManagerInterface $productOfferEntityManager
    ) {
        $this->productOfferRepository = $productOfferRepository;
        $this->productOfferEntityManager = $productOfferEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferResponseTransfer
     */
    public function update(ProductOfferTransfer $productOfferTransfer): ProductOfferResponseTransfer
    {
        $productOfferResponseTransfer = $this->createProductOfferResponseTransfer();

        if (
            !$productOfferTransfer->getIdProductOffer()
            || !$this->productOfferRepository->findOne((new ProductOfferCriteriaTransfer())->setIdProductOffer($productOfferTransfer->getIdProductOffer()))
        ) {
            return $this->addProductOfferError($productOfferResponseTransfer, static::ERROR_MESSAGE_PRODUCT_OFFER_NOT_FOUND);
        }

        $productOfferTransfer = $this->productOfferEntityManager->updateProductOffer($productOfferTransfer);

        return $productOfferResponseTransfer->setIsSuccess(true)
            ->setProductOffer($productOfferTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOfferResponseTransfer
     */
    protected function createProductOfferResponseTransfer(): ProductOfferResponseTransfer
    {
        return (new ProductOfferResponseTransfer())
            ->setIsSuccess(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferResponseTransfer $productOfferResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ProductOfferResponseTransfer
     */
    protected function addProductOfferError(ProductOfferResponseTransfer $productOfferResponseTransfer, string $message): ProductOfferResponseTransfer
    {
        $productOfferResponseTransfer->addError((new ProductOfferErrorTransfer())->setMessage($message));

        return $productOfferResponseTransfer;
    }
}
