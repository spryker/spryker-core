<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Creator;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Laminas\Filter\Word\CamelCaseToUnderscore;

class PriceProductTableColumnCreator implements PriceProductTableColumnCreatorInterface
{
    /**
     * @param string $priceTypeName
     * @param string $moneyValueType
     *
     * @return string
     */
    public function createPriceColumnId(string $priceTypeName, string $moneyValueType): string
    {
        return sprintf(
            '%s[%s][%s]',
            mb_strtolower($priceTypeName),
            PriceProductTransfer::MONEY_VALUE,
            $moneyValueType,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $propertyPathValues
     *
     * @return string
     */
    public function createColumnIdFromPropertyPath(
        PriceProductTransfer $priceProductTransfer,
        array $propertyPathValues
    ): string {
        $fieldName = end($propertyPathValues);

        switch ($fieldName) {
            case MoneyValueTransfer::GROSS_AMOUNT:
            case MoneyValueTransfer::NET_AMOUNT:
                $priceTypeName = $priceProductTransfer
                    ->getPriceTypeOrFail()
                    ->getNameOrFail();

                return $this->createPriceColumnId($priceTypeName, $fieldName);
            case PriceProductTransfer::VOLUME_QUANTITY:
                return PriceProductTableViewTransfer::VOLUME_QUANTITY;
            case MoneyValueTransfer::FK_CURRENCY:
                return PriceProductTableViewTransfer::CURRENCY;
            case MoneyValueTransfer::FK_STORE:
                return PriceProductTableViewTransfer::STORE;
        }

        return (string)$fieldName;
    }

    /**
     * @return string
     */
    public function createVolumeQuantityColumnId(): string
    {
        /** @var string $idVolumeQuantity */
        $idVolumeQuantity = (new CamelCaseToUnderscore())
            ->filter(PriceProductTableViewTransfer::VOLUME_QUANTITY);

        return strtolower($idVolumeQuantity);
    }
}
