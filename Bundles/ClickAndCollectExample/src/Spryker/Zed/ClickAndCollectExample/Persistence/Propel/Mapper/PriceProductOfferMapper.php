<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferPriceTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\ClickAndCollectExample\ClickAndCollectExampleConfig;

class PriceProductOfferMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer> $priceProductOfferEntityCollection
     * @param string $priceMode
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferPriceTransfer>
     */
    public function mapPriceProductOfferEntityCollectionToProductOfferPriceTransfers(
        ObjectCollection $priceProductOfferEntityCollection,
        string $priceMode
    ): array {
        $productOfferPriceTransfers = [];
        foreach ($priceProductOfferEntityCollection as $priceProductOfferEntity) {
            $productOfferPriceTransfer = (new ProductOfferPriceTransfer())
                ->setIdProductOffer($priceProductOfferEntity->getFkProductOffer());
            $productOfferPriceTransfer = $this->mapPriceByPriceMode($priceProductOfferEntity, $productOfferPriceTransfer, $priceMode);

            $productOfferPriceTransfers[] = $productOfferPriceTransfer;
        }

        return $productOfferPriceTransfers;
    }

    /**
     * @param \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer $priceProductOfferEntity
     * @param \Generated\Shared\Transfer\ProductOfferPriceTransfer $productOfferPriceTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ProductOfferPriceTransfer
     */
    protected function mapPriceByPriceMode(
        SpyPriceProductOffer $priceProductOfferEntity,
        ProductOfferPriceTransfer $productOfferPriceTransfer,
        string $priceMode
    ): ProductOfferPriceTransfer {
        if ($priceMode === ClickAndCollectExampleConfig::PRICE_MODE_GROSS) {
            return $productOfferPriceTransfer->setPrice($priceProductOfferEntity->getSpyPriceProductStore()->getGrossPrice());
        }

        return $productOfferPriceTransfer->setPrice($priceProductOfferEntity->getSpyPriceProductStore()->getNetPrice());
    }
}
