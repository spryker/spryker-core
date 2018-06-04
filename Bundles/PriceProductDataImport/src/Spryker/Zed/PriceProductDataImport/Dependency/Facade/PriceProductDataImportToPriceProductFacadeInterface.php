<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\PriceProductDataImport\Dependency\Facade;

interface PriceProductDataImportToPriceProductFacadeInterface
{
    /**
     * @param array $priceData
     *
     * @return string
     */
    public function generatePriceDataChecksum(array $priceData): string;
}
