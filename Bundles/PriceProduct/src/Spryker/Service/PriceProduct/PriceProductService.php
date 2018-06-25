<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
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
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function resolveProductPriceByPriceProductCriteria(
        array $priceProductTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?PriceProductTransfer {
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
    ): ?PriceProductTransfer {
        return $this->getFactory()
            ->createPriceProductMatcher()
            ->matchPriceValueByPriceProductFilter($priceProductTransferCollection, $priceProductFilterTransfer);
    }
}
