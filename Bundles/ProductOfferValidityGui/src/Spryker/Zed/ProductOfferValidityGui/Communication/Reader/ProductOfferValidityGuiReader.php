<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidityGui\Communication\Reader;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Spryker\Zed\ProductOfferValidityGui\Dependency\Facade\ProductOfferValidityGuiToProductOfferValidityFacadeInterface;

class ProductOfferValidityGuiReader implements ProductOfferValidityGuiReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferValidityGui\Dependency\Facade\ProductOfferValidityGuiToProductOfferValidityFacadeInterface
     */
    protected $productOfferValidityFacade;

    /**
     * @param \Spryker\Zed\ProductOfferValidityGui\Dependency\Facade\ProductOfferValidityGuiToProductOfferValidityFacadeInterface $productOfferValidityFacade
     */
    public function __construct(ProductOfferValidityGuiToProductOfferValidityFacadeInterface $productOfferValidityFacade)
    {
        $this->productOfferValidityFacade = $productOfferValidityFacade;
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    public function getProductOfferValidityData(ProductOfferTransfer $productOfferTransfer): array
    {
        $productOfferValidityTransfer = $this->productOfferValidityFacade
            ->findProductOfferValidityByIdProductOffer($productOfferTransfer->getIdProductOffer());

        if (!$productOfferValidityTransfer) {
            $productOfferValidityTransfer = new ProductOfferValidityTransfer();
        }

        return $productOfferValidityTransfer->toArray();
    }
}
