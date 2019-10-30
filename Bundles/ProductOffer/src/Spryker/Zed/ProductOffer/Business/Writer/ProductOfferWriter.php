<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Writer;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface;

class ProductOfferWriter implements ProductOfferWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface
     */
    protected $productOfferEntityManager;

    /**
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface $productOfferEntityManager
     */
    public function __construct(ProductOfferEntityManagerInterface $productOfferEntityManager)
    {
        $this->productOfferEntityManager = $productOfferEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function create(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->productOfferEntityManager->createProductOffer($productOfferTransfer);
    }
}
