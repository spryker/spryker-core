<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use Spryker\PriceProductOffer\src\Spryker\Shared\PriceProductOffer\PriceProductOfferConfig;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferPersistenceFactory getFactory()
 */
class PriceProductOfferRepository extends AbstractRepository implements PriceProductOfferRepositoryInterface
{
    /**
     * @param string[] $skus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductConcreteTransfers(array $skus, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        $priceProductOfferEntities = $this->getFactory()
            ->getPriceProductOfferPropelQuery()
            ->joinWithSpyProductOffer()
            ->useSpyProductOfferQuery()
                ->filterByConcreteSku_In($skus)
            ->endUse()
            ->joinWithSpyPriceType()
            ->joinWithSpyCurrency()
            ->find();

        $priceProductTransfers = [];

        foreach ($priceProductOfferEntities as $priceProductOfferEntity) {
            $priceProductTransfers[] = $this->mapPriceProductOfferEntityToPriceProductTransfer($priceProductOfferEntity, new PriceProductTransfer());
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer $priceProductOfferEntity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapPriceProductOfferEntityToPriceProductTransfer(SpyPriceProductOffer $priceProductOfferEntity, PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer
            ->setSkuProduct($priceProductOfferEntity->getSpyProductOffer()->getConcreteSku())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(PriceProductOfferConfig::DIMENSION_TYPE)
                    ->setProductOfferReference($priceProductOfferEntity->getSpyProductOffer()->getProductOfferReference())
            )
            ->setItemIdentifier($priceProductOfferEntity->getSpyProductOffer()->getProductOfferReference())
            ->setIsMergeable(false)
            ->setPriceTypeName($priceProductOfferEntity->getSpyPriceType()->getName())
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setFkStore($priceProductOfferEntity->getFkStore())
                    ->setCurrency(
                        (new CurrencyTransfer())->fromArray($priceProductOfferEntity->getSpyCurrency()->toArray(), true)
                    )
                    ->setNetAmount($priceProductOfferEntity->getNetPrice())
                    ->setGrossAmount($priceProductOfferEntity->getGrossPrice())
            );

        return $priceProductTransfer;
    }
}
