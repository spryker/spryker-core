<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface;

class PreparePriceDataStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PriceProductOfferDataImportToPriceProductFacadeInterface $priceProductFacade,
        DataImportToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceData = $this->getPriceData($dataSet);

        if (!$priceData) {
            $dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA] = null;
            $dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA_CHECKSUM] = null;

            return;
        }

        $dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA] = $this->utilEncodingService->encodeJson($priceData);
        $dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA_CHECKSUM] = $this->priceProductFacade
            ->generatePriceDataChecksum($priceData);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array
     */
    protected function getPriceData(DataSetInterface $dataSet): array
    {
        $volumePrices = $this->utilEncodingService->decodeJson(
            $dataSet[PriceProductOfferDataSetInterface::PRICE_DATA_VOLUME_PRICES],
            true
        );

        return [
            PriceProductOfferDataSetInterface::VOLUME_PRICES => $volumePrices,
        ];
    }
}
