<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Laminas\Filter\StringToLower;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductTableDataMapper
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
     * @param mixed[] $priceProductTableDataArray
     * @param \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer
     */
    public function mapPriceProductTableDataArrayToPriceProductTableViewCollectionTransfer(
        array $priceProductTableDataArray,
        PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer
    ): PriceProductTableViewCollectionTransfer {
        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();

        foreach ($priceProductTableDataArray as $priceProductTableRowDataArray) {
            $priceProductTableRowDataArray[PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS] = explode(
                ',',
                $priceProductTableRowDataArray[PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS]
            );

            $priceProductTableViewTransfer = (new PriceProductTableViewTransfer())
                ->fromArray($priceProductTableRowDataArray, true)
                ->setPrices($this->preparePrices($priceProductTableRowDataArray, $priceTypeTransfers));

            $priceProductTableViewCollectionTransfer->addPriceProductTableView($priceProductTableViewTransfer);
        }

        return $priceProductTableViewCollectionTransfer;
    }

    /**
     * @phpstan-param array<mixed> $priceProductTableRowDataArray
     * @phpstan-param array<\Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @param array $priceProductTableRowDataArray
     * @param \Generated\Shared\Transfer\PriceTypeTransfer[] $priceTypeTransfers
     *
     * @return mixed[]
     */
    protected function preparePrices(array $priceProductTableRowDataArray, array $priceTypeTransfers): array
    {
        $prices = [];

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $priceTypeName = (new StringToLower())->filter($priceTypeTransfer->getNameOrFail());
            $keyNetPrice = $priceTypeName . static::SUFFIX_PRICE_TYPE_NET;
            $keyGrossPrice = $priceTypeName . static::SUFFIX_PRICE_TYPE_GROSS;

            if (array_key_exists($keyGrossPrice, $priceProductTableRowDataArray)) {
                $prices[$this->createGrossKey($priceTypeName)] = $priceProductTableRowDataArray[$keyGrossPrice];
            }

            if (array_key_exists($keyNetPrice, $priceProductTableRowDataArray)) {
                $prices[$this->createNetKey($priceTypeName)] = $priceProductTableRowDataArray[$keyNetPrice];
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
