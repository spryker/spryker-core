<?php

namespace Spryker\Service\PriceProduct;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\PriceProduct\PriceProductServiceFactory getFactory()
 */
class PriceProductService extends AbstractService implements PriceProductServiceInterface
{

    /**
     * @param array $priceProductTransferCollection
     * @param PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return CurrentProductPriceTransfer
     */
    public function resolveProductPrice(
        array $priceProductTransferCollection,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): CurrentProductPriceTransfer {
        return $this->getFactory()
            ->createPriceProductMatcher()
            ->matchPriceValue($priceProductTransferCollection, $priceProductCriteriaTransfer);
    }
}
