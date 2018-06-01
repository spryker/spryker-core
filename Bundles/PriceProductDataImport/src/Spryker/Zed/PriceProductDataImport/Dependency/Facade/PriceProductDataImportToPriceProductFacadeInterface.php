<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\PriceProductDataImport\Dependency\Facade;

interface PriceProductDataImportToPriceProductFacadeInterface
{
    /**
     * @param string $priceData
     *
     * @return string
     */
    public function generatePriceDataChecksum(string $priceData): string;
}
