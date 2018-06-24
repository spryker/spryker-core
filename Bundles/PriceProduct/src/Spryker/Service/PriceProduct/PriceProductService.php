<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\PriceProduct\PriceProductServiceFactory getFactory()
 */
class PriceProductService extends AbstractService implements PriceProductServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    public function resolveProductPriceByPriceProductCriteria(
        array $priceProductTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?int {
        return $this->getFactory()
            ->createPriceProductMatcher()
            ->matchPriceValueByPriceProductCriteria($priceProductTransferCollection, $priceProductCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return int|null
     */
    public function resolveProductPriceByPriceProductFilter(
        array $priceProductTransferCollection,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): ?int {
        return $this->getFactory()
            ->createPriceProductMatcher()
            ->matchPriceValueByPriceProductFilter($priceProductTransferCollection, $priceProductFilterTransfer);
    }

    /**
     * @param array $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    public function resolvePriceProductDimensionByPriceProductFilter(
        array $priceProductTransferCollection,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductDimensionTransfer {
        return $this->getFactory()
            ->createPriceProductMatcher()
            ->matchPriceProductDimensionByPriceProductFilter($priceProductTransferCollection, $priceProductFilterTransfer);
    }
}
