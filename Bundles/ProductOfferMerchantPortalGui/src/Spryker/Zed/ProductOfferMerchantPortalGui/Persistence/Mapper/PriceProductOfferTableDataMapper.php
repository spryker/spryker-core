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
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::COL_STORE
     */
    protected const COL_STORE = 'store';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::COL_CURRENCY
     */
    protected const COL_CURRENCY = 'currency';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::SUFFIX_PRICE_TYPE_NET
     */
    protected const SUFFIX_PRICE_TYPE_NET = '_net';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::SUFFIX_PRICE_TYPE_GROSS
     */
    protected const SUFFIX_PRICE_TYPE_GROSS = '_gross';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\AbstractPriceProductOfferGuiTableConfigurationProvider::ID_COLUMN_SUFFIX_PRICE_TYPE_NET
     */
    protected const SUFFIX_PRICE_TYPE_NET_AMOUNT = '[moneyValue][netAmount]';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\AbstractPriceProductOfferGuiTableConfigurationProvider::ID_COLUMN_SUFFIX_PRICE_TYPE_GROSS
     */
    protected const SUFFIX_PRICE_TYPE_GROSS_AMOUNT = '[moneyValue][grossAmount]';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::COL_PRICE_PRODUCT_OFFER_IDS
     */
    protected const COL_PRICE_PRODUCT_OFFER_IDS = 'price_product_offer_ids';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::COL_TYPE_PRICE_PRODUCT_OFFER_IDS
     */
    protected const COL_TYPE_PRICE_PRODUCT_OFFER_IDS = 'type_price_product_offer_ids';

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
     * @phpstan-param array<mixed> $priceProductOfferTableDataArray
     *
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
                $priceProductOfferTransfers[$priceKey]->setPrices($prices[$priceKey])
                    ->addPriceProductOfferId(
                        $priceProductOfferTableRowDataArray[static::COL_PRICE_PRODUCT_OFFER_IDS]
                    )
                    ->addTypePriceProductOfferId(
                        $priceProductOfferTableRowDataArray[static::COL_TYPE_PRICE_PRODUCT_OFFER_IDS]
                    );

                continue;
            }

            $priceProductOfferTransfer = (new PriceProductOfferTransfer())
                ->setStore($priceProductOfferTableRowDataArray[static::COL_STORE])
                ->setCurrency($priceProductOfferTableRowDataArray[static::COL_CURRENCY])
                ->setPrices($prices[$priceKey])
                ->addPriceProductOfferId($priceProductOfferTableRowDataArray[static::COL_PRICE_PRODUCT_OFFER_IDS])
                ->addTypePriceProductOfferId($priceProductOfferTableRowDataArray[static::COL_TYPE_PRICE_PRODUCT_OFFER_IDS]);

            $priceProductOfferTransfers[$priceKey] = $priceProductOfferTransfer;
        }

        return $priceProductConcreteCollectionTransfer->setPriceProductOffers(
            new ArrayObject($priceProductOfferTransfers)
        );
    }

    /**
     * @phpstan-param array<mixed> $prices
     * @phpstan-param array<mixed> $priceProductOfferTableRowDataArray
     *
     * @phpstan-return array<mixed>
     *
     * @param array $prices
     * @param array $priceProductOfferTableRowDataArray
     *
     * @return array
     */
    protected function preparePrices(array $prices, array $priceProductOfferTableRowDataArray): array
    {
        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $priceTypeName = mb_strtolower($priceTypeTransfer->getName());
            $keyNetPrice = $priceTypeName . static::SUFFIX_PRICE_TYPE_NET;
            $keyGrossPrice = $priceTypeName . static::SUFFIX_PRICE_TYPE_GROSS;

            if ($priceProductOfferTableRowDataArray[$keyGrossPrice]) {
                $prices[$priceTypeName . static::SUFFIX_PRICE_TYPE_GROSS_AMOUNT] = $priceProductOfferTableRowDataArray[$keyGrossPrice];
            }

            if ($priceProductOfferTableRowDataArray[$keyNetPrice]) {
                $prices[$priceTypeName . static::SUFFIX_PRICE_TYPE_NET_AMOUNT] = $priceProductOfferTableRowDataArray[$keyNetPrice];
            }
        }

        return $prices;
    }
}
