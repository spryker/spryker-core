<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewCollectionTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductAbstractTableDataMapper
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepository::SUFFIX_PRICE_TYPE_NET
     */
    protected const SUFFIX_PRICE_TYPE_NET = '_net';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepository::SUFFIX_PRICE_TYPE_GROSS
     */
    protected const SUFFIX_PRICE_TYPE_GROSS = '_gross';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param mixed[] $priceProductAbstractTableDataArray
     * @param \Generated\Shared\Transfer\PriceProductAbstractTableViewCollectionTransfer $priceProductAbstractTableViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductAbstractTableViewCollectionTransfer
     */
    public function mapPriceProductAbstractTableDataArrayToPriceProductAbstractTableViewCollectionTransfer(
        array $priceProductAbstractTableDataArray,
        PriceProductAbstractTableViewCollectionTransfer $priceProductAbstractTableViewCollectionTransfer
    ): PriceProductAbstractTableViewCollectionTransfer {
        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();

        foreach ($priceProductAbstractTableDataArray as $priceProductAbstractTableRowDataArray) {
            $priceProductAbstractTableRowDataArray[PriceProductAbstractTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS] = explode(
                ',',
                $priceProductAbstractTableRowDataArray[PriceProductAbstractTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS]
            );

            $priceProductAbstractTableViewTransfer = (new PriceProductAbstractTableViewTransfer())
                ->fromArray($priceProductAbstractTableRowDataArray, true)
                ->setPrices($this->preparePrices($priceProductAbstractTableRowDataArray, $priceTypeTransfers));

            $priceProductAbstractTableViewCollectionTransfer->addPriceProductAbstractTableView($priceProductAbstractTableViewTransfer);
        }

        return $priceProductAbstractTableViewCollectionTransfer;
    }

    /**
     * @phpstan-param array<mixed> $priceProductAbstractTableRowDataArray
     * @phpstan-param array<\Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @param array $priceProductAbstractTableRowDataArray
     * @param \Generated\Shared\Transfer\PriceTypeTransfer[] $priceTypeTransfers
     *
     * @return mixed[]
     */
    protected function preparePrices(array $priceProductAbstractTableRowDataArray, array $priceTypeTransfers): array
    {
        $prices = [];

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $priceTypeName = mb_strtolower($priceTypeTransfer->getName());
            $keyNetPrice = $priceTypeName . static::SUFFIX_PRICE_TYPE_NET;
            $keyGrossPrice = $priceTypeName . static::SUFFIX_PRICE_TYPE_GROSS;

            if (array_key_exists($keyGrossPrice, $priceProductAbstractTableRowDataArray)) {
                $prices[$this->createGrossKey($priceTypeName)] = $priceProductAbstractTableRowDataArray[$keyGrossPrice];
            }

            if (array_key_exists($keyNetPrice, $priceProductAbstractTableRowDataArray)) {
                $prices[$this->createNetKey($priceTypeName)] = $priceProductAbstractTableRowDataArray[$keyNetPrice];
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
