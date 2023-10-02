<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class ProductOfferStockMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock> $productOfferStockEntityCollection
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferStockTransfer>
     */
    public function mapProductOfferStockEntityCollectionToProductOfferStockTransfers(
        ObjectCollection $productOfferStockEntityCollection
    ): array {
        $productOfferStockTransfers = [];
        foreach ($productOfferStockEntityCollection as $productOfferStockEntity) {
            $productOfferStockTransfer = (new ProductOfferStockTransfer())
                ->fromArray($productOfferStockEntity->toArray(), true)
                ->setIdProductOffer($productOfferStockEntity->getFkProductOffer());

            $productOfferStockTransfers[] = $productOfferStockTransfer;
        }

        return $productOfferStockTransfers;
    }
}
