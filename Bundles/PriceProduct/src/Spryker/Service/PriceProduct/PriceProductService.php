<?php

namespace Spryker\Service\PriceProduct;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\PriceProduct\PriceProductServiceFactory getFactory()
 */
class PriceProductService extends AbstractService implements PriceProductServiceInterface
{

    /**
     * @param PriceProductTransfer[] $priceProductTransferCollection
     * @param PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function resolveProductPrice(
        array $priceProductTransferCollection,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): ?MoneyValueTransfer {
        return $this->getFactory()
            ->createPriceProductMatcher()
            ->matchPriceValue($priceProductTransferCollection, $priceProductCriteriaTransfer);
    }
}
