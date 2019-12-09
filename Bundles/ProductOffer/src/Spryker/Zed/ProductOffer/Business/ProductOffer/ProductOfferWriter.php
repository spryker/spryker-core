<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\ProductOffer;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOffer\Business\Exception\ProductOfferNotFoundException;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class ProductOfferWriter implements ProductOfferWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface
     */
    protected $productOfferRepository;

    /**
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface $productOfferRepository
     */
    public function __construct(ProductOfferRepositoryInterface $productOfferRepository)
    {
        $this->productOfferRepository = $productOfferRepository;
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function activateProductOfferById(int $idProductOffer): ProductOfferTransfer
    {
        $productOfferTransfer = $this->getProductOfferById($idProductOffer);

        if ($productOfferTransfer->getIsActive()) {
            return $productOfferTransfer;
        }

        $productOfferTransfer = $this->productOfferRepository->activateProductOffer($productOfferTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function deactivateProductOfferById(int $idProductOffer): ProductOfferTransfer
    {
        $productOfferTransfer = $this->getProductOfferById($idProductOffer);

        if (!$productOfferTransfer->getIsActive()) {
            return $productOfferTransfer;
        }

        $productOfferTransfer = $this->productOfferRepository->deactivateProductOffer($productOfferTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param int $idProductOffer
     *
     * @throws \Spryker\Zed\ProductOffer\Business\Exception\ProductOfferNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function getProductOfferById(int $idProductOffer): ProductOfferTransfer
    {
        $productOfferCriteriaFilterTransfer = new ProductOfferCriteriaFilterTransfer();
        $productOfferCriteriaFilterTransfer->setIdProductOffer($idProductOffer);
        $productOfferTransfer = $this->productOfferRepository->findOne($productOfferCriteriaFilterTransfer);

        if (!$productOfferTransfer) {
            throw new ProductOfferNotFoundException(sprintf(
                'Product offer with ID "%d" not found.',
                $idProductOffer
            ));
        }
        $productOfferTransfer->requireIdProductOffer();

        return $productOfferTransfer;
    }
}
