<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductOfferTableDataMapper
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param array $priceProductOfferTableDataArray
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer
     */
    public function mapPriceProductOfferTableDataArrayToPriceProductOfferCollectionTransfer(
        array $priceProductOfferTableDataArray,
        PriceProductOfferCollectionTransfer $priceProductConcreteCollectionTransfer
    ): PriceProductOfferCollectionTransfer {
        $priceProductOfferTransfers = [];

        foreach ($priceProductOfferTableDataArray as $priceProductOfferTableRowDataArray) {
            $priceProductOfferTableRowDataArray = $priceProductOfferTableRowDataArray->toArray();
            $priceKey = $priceProductOfferTableRowDataArray['store'] . '_' . $priceProductOfferTableRowDataArray['currency'];

            if (!isset($prices[$priceKey])) {
                $prices[$priceKey] = [];
            }

            $prices[$priceKey] = $this->preparePrices($prices[$priceKey], $priceProductOfferTableRowDataArray);

            if (isset($priceProductOfferTransfers[$priceKey])) {
                $priceProductOfferTransfers[$priceKey]->setPrices($prices[$priceKey]);

                continue;
            }

            $priceProductOfferTransfer = (new PriceProductOfferTransfer())
                ->setStore($priceProductOfferTableRowDataArray['store'])
                ->setCurrency($priceProductOfferTableRowDataArray['currency'])
                ->setPrices($prices[$priceKey]);

            $priceProductOfferTransfers[$priceKey] = $priceProductOfferTransfer;
        }

        return $priceProductConcreteCollectionTransfer->setPriceProductOffers(
            new ArrayObject($priceProductOfferTransfers)
        );
    }

    /**
     * @param array $priceProductOfferTableRowDataArray
     * @return array
     */
    protected function preparePrices(array $prices, array $priceProductOfferTableRowDataArray): array
    {
        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $keyNetPrice = mb_strtolower($priceTypeTransfer->getName()) . '_net';
            $keyGrossPrice = mb_strtolower($priceTypeTransfer->getName()) . '_gross';

            if ($priceProductOfferTableRowDataArray[$keyGrossPrice]) {
                $prices[$keyGrossPrice] = $priceProductOfferTableRowDataArray[$keyGrossPrice];
            }

            if ($priceProductOfferTableRowDataArray[$keyNetPrice]) {
                $prices[$keyNetPrice] = $priceProductOfferTableRowDataArray[$keyNetPrice];
            }
        }

        return $prices;
    }
}
