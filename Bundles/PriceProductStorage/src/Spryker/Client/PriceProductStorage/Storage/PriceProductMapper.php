<?php
/**
 * Created by PhpStorm.
 * User: matveyev
 * Date: 6/26/18
 * Time: 19:36
 */

namespace Spryker\Client\PriceProductStorage\Storage;


use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProduct\PriceProductConstants;

class PriceProductMapper
{

    /**
     * @param \Generated\Shared\Transfer\PriceProductStorageTransfer $priceProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductStorageTransferToPriceProductTransfers(
        PriceProductStorageTransfer $priceProductStorageTransfer
    ): array {
        /** @var \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers */
        $priceProductTransfers = [];

        foreach ($priceProductStorageTransfer->getPrices() as $currencyCode => $prices) {
            foreach ($prices as $priceMode => $priceTypes) {
                foreach ($priceTypes as $priceType => $priceAmount) {
                    $index = implode('-', [
                        $currencyCode,
                        $priceType,
                    ]);
                    if (!isset($priceProductTransfers[$index])) {
                        $priceProductTransfers[$index] = (new PriceProductTransfer())
                            ->setPriceDimension(
                                (new PriceProductDimensionTransfer())
                                    ->setType(PriceProductConstants::PRICE_DIMENSION_DEFAULT)
                            )
                            ->setMoneyValue(
                                (new MoneyValueTransfer())
                                    ->setCurrency((new CurrencyTransfer())->setCode($currencyCode))
                            )
                            ->setPriceTypeName($priceType);
                    }
                    if ($priceMode === 'GROSS_MODE') {
                        $priceProductTransfers[$index]->getMoneyValue()->setGrossAmount($priceAmount);
                        continue;
                    }

                    $priceProductTransfers[$index]->getMoneyValue()->setNetAmount($priceAmount);
                }
            }
        }

        return array_values($priceProductTransfers);
    }
}
