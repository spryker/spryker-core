<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Reader;

use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface;

class AddToCartSkuReader implements AddToCartSkuReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface
     */
    protected $productPageSearchRepository;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface $productPageSearchRepository
     */
    public function __construct(ProductPageSearchRepositoryInterface $productPageSearchRepository)
    {
        $this->productPageSearchRepository = $productPageSearchRepository;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array<int, string>
     */
    public function getProductAbstractAddToCartSkus(array $productAbstractIds): array
    {
        $productAbstractIds = $this->productPageSearchRepository->getEligibleForAddToCartProductAbstractsIds($productAbstractIds);

        if (!$productAbstractIds) {
            return [];
        }

        return $this->productPageSearchRepository->getProductAbstractAddToCartSkus($productAbstractIds);
    }
}
