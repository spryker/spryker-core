<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Mapper;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductOfferTableDataMapper
{
    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::SUFFIX_PRICE_TYPE_NET
     */
    protected const SUFFIX_PRICE_TYPE_NET = '_net';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepository::SUFFIX_PRICE_TYPE_GROSS
     */
    protected const SUFFIX_PRICE_TYPE_GROSS = '_gross';

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
     * @param \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer
     */
    public function mapPriceProductOfferTableDataArrayToPriceProductOfferTableViewCollectionTransfer(
        array $priceProductOfferTableDataArray,
        PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
    ): PriceProductOfferTableViewCollectionTransfer {
        foreach ($priceProductOfferTableDataArray as $priceProductOfferTableRowDataArray) {
            $prices = $this->preparePrices($priceProductOfferTableRowDataArray);

            $priceProductOfferTableViewTransfer = (new PriceProductOfferTableViewTransfer())
                ->setStore($priceProductOfferTableRowDataArray[PriceProductOfferTableViewTransfer::STORE])
                ->setCurrency($priceProductOfferTableRowDataArray[PriceProductOfferTableViewTransfer::CURRENCY])
                ->setPrices($prices)
                ->addPriceProductOfferId($priceProductOfferTableRowDataArray[static::COL_PRICE_PRODUCT_OFFER_IDS])
                ->setTypePriceProductOfferIds($priceProductOfferTableRowDataArray[static::COL_TYPE_PRICE_PRODUCT_OFFER_IDS]);

            $priceProductOfferTableViewCollectionTransfer->addPriceProductOfferTableView($priceProductOfferTableViewTransfer);
        }

        return $priceProductOfferTableViewCollectionTransfer;
    }

    /**
     * @phpstan-param array<mixed> $priceProductOfferTableRowDataArray
     *
     * @phpstan-return array<mixed>
     *
     * @param array $priceProductOfferTableRowDataArray
     *
     * @return array
     */
    protected function preparePrices(array $priceProductOfferTableRowDataArray): array
    {
        $prices = [];

        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $priceTypeName = mb_strtolower($priceTypeTransfer->getName());
            $keyNetPrice = $priceTypeName . static::SUFFIX_PRICE_TYPE_NET;
            $keyGrossPrice = $priceTypeName . static::SUFFIX_PRICE_TYPE_GROSS;

            if (isset($priceProductOfferTableRowDataArray[$keyGrossPrice])) {
                $prices[$this->createGrossKey($priceTypeName)] = $priceProductOfferTableRowDataArray[$keyGrossPrice];
            }

            if (isset($priceProductOfferTableRowDataArray[$keyNetPrice])) {
                $prices[$this->createNetKey($priceTypeName)] = $priceProductOfferTableRowDataArray[$keyNetPrice];
            }
        }

        return $prices;
    }

    /**
     * @param string $pryceTypeName
     *
     * @return string
     */
    protected function createGrossKey(string $pryceTypeName): string
    {
        return sprintf(
            '%s[%s][%s]',
            $pryceTypeName,
            PriceProductTransfer::MONEY_VALUE,
            MoneyValueTransfer::GROSS_AMOUNT
        );
    }

    /**
     * @param string $pryceTypeName
     *
     * @return string
     */
    protected function createNetKey(string $pryceTypeName): string
    {
        return sprintf(
            '%s[%s][%s]',
            $pryceTypeName,
            PriceProductTransfer::MONEY_VALUE,
            MoneyValueTransfer::NET_AMOUNT
        );
    }
}
