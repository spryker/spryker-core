<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferServicePointTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Propel\Runtime\Collection\ObjectCollection;

class ProductOfferMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productOfferEntityCollection
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function mapProductOfferEntityCollectionToProductOfferServicePointTransfers(
        ObjectCollection $productOfferEntityCollection
    ): array {
        $productOfferServicePointTransfers = [];
        foreach ($productOfferEntityCollection as $productOfferEntity) {
            $productOfferServicePointTransfers[] =
                $this->mapProductOfferEntityToProductOfferServicePointTransfer(
                    $productOfferEntity,
                    new ProductOfferServicePointTransfer(),
                );
        }

        return $productOfferServicePointTransfers;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $productOfferEntity
     * @param \Generated\Shared\Transfer\ProductOfferServicePointTransfer $productOfferServicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointTransfer
     */
    protected function mapProductOfferEntityToProductOfferServicePointTransfer(
        SpyProductOffer $productOfferEntity,
        ProductOfferServicePointTransfer $productOfferServicePointTransfer
    ): ProductOfferServicePointTransfer {
        $productOfferServicePointTransfer->setProductOffer(
            (new ProductOfferTransfer())->fromArray($productOfferEntity->toArray(), true),
        );

        foreach ($productOfferEntity->getProductOfferServices() as $productOfferServiceEntity) {
            $productOfferServicePointTransfer->addServicePoint(
                (new ServicePointTransfer())->fromArray(
                    $productOfferServiceEntity->getService()->getServicePoint()->toArray(),
                    true,
                ),
            );
        }

        return $productOfferServicePointTransfer;
    }
}
