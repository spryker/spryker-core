<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\VolumePriceProduct\Plugin\PriceProductExtension;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductMapperPricesExtractorPluginInterface;

class VolumePriceProductExtractorPlugin extends AbstractPlugin implements PriceProductMapperPricesExtractorPluginInterface
{

    public function extractProductPrices(
        PriceProductTransfer $priceProductTransfer
    ): array
    {
        if (!$priceProductTransfer->getMoneyValue()->getPriceData()) {
            return [];
        }

        //todo: move to plugin stack
        $result = [];
        $priceData = json_decode($priceProductTransfer->getMoneyValue()->getPriceData(), true);
        foreach ($priceData['volume_prices'] as $volumePrice) {
            $volumePriceTransfer = clone $priceProductTransfer;
            $volumePriceTransfer->getMoneyValue()->setGrossAmount($volumePrice['gross_price'])
                ->setNetAmount($volumePrice['net_price']);
            $volumePriceTransfer->setQuantityToApply($volumePrice['quantity']);
            $result[] = $volumePriceTransfer;
        }

        return $result;
    }
}
