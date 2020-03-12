<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade;

class PriceProductOfferDataImportToPriceProductFacadeBridge implements PriceProductOfferDataImportToPriceProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     */
    public function __construct($priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param array $priceData
     *
     * @return string
     */
    public function generatePriceDataChecksum(array $priceData): string
    {
        return $this->priceProductFacade->generatePriceDataChecksum($priceData);
    }
}
