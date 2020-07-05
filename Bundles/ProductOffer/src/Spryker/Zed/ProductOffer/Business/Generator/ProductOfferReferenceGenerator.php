<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Generator;

use Spryker\Zed\ProductOffer\Business\Exception\ProductOfferReferenceNotCreatedException;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class ProductOfferReferenceGenerator implements ProductOfferReferenceGeneratorInterface
{
    protected const PREFIX_PRODUCT_OFFER_REFERENCE = 'offer';

    protected const PRODUCT_OFFER_REFERENCE_GENERATOR_ITERATION_LIMIT = 10;

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
     * @throws \Spryker\Zed\ProductOffer\Business\Exception\ProductOfferReferenceNotCreatedException
     *
     * @return string
     */
    public function generateProductOfferReference(): string
    {
        $index = $this->productOfferRepository->getMaxIdProductOffer() + 1;

        $attempt = 0;
        do {
            if ($attempt >= static::PRODUCT_OFFER_REFERENCE_GENERATOR_ITERATION_LIMIT) {
                throw new ProductOfferReferenceNotCreatedException(
                    'Cannot create product offer reference: maximum iterations threshold met.'
                );
            }

            $productOfferReference = sprintf('%s%d', static::PREFIX_PRODUCT_OFFER_REFERENCE, $index);
            $index++;
            $attempt++;
        } while ($this->productOfferRepository->isProductOfferReferenceUsed($productOfferReference));

        return $productOfferReference;
    }
}
