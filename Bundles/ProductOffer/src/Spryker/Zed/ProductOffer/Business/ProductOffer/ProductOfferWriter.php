<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class ProductOfferWriter implements ProductOfferWriterInterface
{
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
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function activateProductOfferById(int $idProductOffer): ?ProductOfferTransfer
    {
        $productOfferTransfer = new ProductOfferTransfer();
        $productOfferTransfer->setIdProductOffer($idProductOffer);
        $productOfferTransfer->setIsActive(true);

        return $this->productOfferEntityManager->updateProductOffer($productOfferTransfer);
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function deactivateProductOfferById(int $idProductOffer): ?ProductOfferTransfer
    {
        $productOfferTransfer = new ProductOfferTransfer();
        $productOfferTransfer->setIdProductOffer($idProductOffer);
        $productOfferTransfer->setIsActive(false);

        return $this->productOfferEntityManager->updateProductOffer($productOfferTransfer);
    }
}
