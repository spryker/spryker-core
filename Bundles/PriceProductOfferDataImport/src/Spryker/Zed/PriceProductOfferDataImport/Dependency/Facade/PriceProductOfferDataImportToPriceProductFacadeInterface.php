<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade;

interface PriceProductOfferDataImportToPriceProductFacadeInterface
{
    /**
     * @param array $priceData
     *
     * @return string
     */
    public function generatePriceDataChecksum(array $priceData): string;
}
