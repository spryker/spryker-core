<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Exporter;

use DateTime;
use Generated\Shared\Transfer\CsvFileTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PriceProductScheduleCsvExporter implements PriceProductScheduleCsvExporterInterface
{
    protected const HEADER_ABSTRACT_SKU = 'abstract_sku';
    protected const HEADER_CONCRETE_SKU = 'concrete_sku';
    protected const HEADER_PRICE_TYPE = 'price_type';
    protected const HEADER_STORE = 'store';
    protected const HEADER_CURRENCY = 'currency';
    protected const HEADER_NET_PRICE = 'value_net';
    protected const HEADER_GROSS_PRICE = 'value_gross';
    protected const HEADER_FROM = 'from_included';
    protected const HEADER_TO = 'to_included';

    protected const FORMAT_FILE_NAME = '%s.csv';
    protected const PARAM_FILE_NAME = 'price_product_schedule_export';

    protected const PATTERN_DATE_TIME = 'Y-m-d\TH:i:s-00:00';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface
     */
    protected $priceProductScheduleFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface
     */
    protected $utilCsvService;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeInterface $priceProductScheduleFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface $utilCsvService
     */
    public function __construct(
        PriceProductScheduleGuiToPriceProductScheduleFacadeInterface $priceProductScheduleFacade,
        PriceProductScheduleGuiToUtilCsvServiceInterface $utilCsvService
    ) {
        $this->priceProductScheduleFacade = $priceProductScheduleFacade;
        $this->utilCsvService = $utilCsvService;
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportToCsvFile(int $idPriceProductScheduleList): StreamedResponse
    {
        $priceProductScheduleCollection = $this->priceProductScheduleFacade
            ->findPriceProductSchedulesByIdPriceProductScheduleList($idPriceProductScheduleList);
        $csvFileTransfer = $this->createCsvFileTransfer($this->prepareItemsForExport($priceProductScheduleCollection));

        return $this->utilCsvService->exportFile($csvFileTransfer);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CsvFileTransfer
     */
    protected function createCsvFileTransfer(array $data): CsvFileTransfer
    {
        $csvFileTransfer = new CsvFileTransfer();
        $csvFileTransfer = $this->setHeaders($csvFileTransfer);
        $csvFileTransfer->setFileName(sprintf(static::FORMAT_FILE_NAME, static::PARAM_FILE_NAME));
        $csvFileTransfer->setData($data);

        return $csvFileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     *
     * @return \Generated\Shared\Transfer\CsvFileTransfer
     */
    protected function setHeaders(CsvFileTransfer $csvFileTransfer): CsvFileTransfer
    {
        return $csvFileTransfer->setHeader([
            static::HEADER_ABSTRACT_SKU,
            static::HEADER_CONCRETE_SKU,
            static::HEADER_PRICE_TYPE,
            static::HEADER_STORE,
            static::HEADER_CURRENCY,
            static::HEADER_NET_PRICE,
            static::HEADER_GROSS_PRICE,
            static::HEADER_FROM,
            static::HEADER_TO,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer[] $priceProductScheduleTransferCollection
     *
     * @return array
     */
    protected function prepareItemsForExport(array $priceProductScheduleTransferCollection): array
    {
        $dataForExport = [];

        foreach ($priceProductScheduleTransferCollection as $priceProductScheduleTransfer) {
            $dataForExport[] = $this->generateItem($priceProductScheduleTransfer);
        }

        return $dataForExport;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return array
     */
    protected function generateItem(PriceProductScheduleTransfer $priceProductScheduleTransfer): array
    {
        $priceProductScheduleTransfer->requireIdPriceProductSchedule()
            ->requirePriceProduct()
            ->getPriceProduct()
                ->requireMoneyValue()
                ->getMoneyValue()
                    ->requireStore()
                    ->requireCurrency();

        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        $dateFrom = new DateTime($priceProductScheduleTransfer->getActiveFrom());
        $dateTo = new DateTime($priceProductScheduleTransfer->getActiveTo());

        return [
            static::HEADER_ABSTRACT_SKU => $this->getSkuProductAsbtract($priceProductTransfer),
            static::HEADER_CONCRETE_SKU => $priceProductTransfer->getSkuProduct(),
            static::HEADER_PRICE_TYPE => $priceProductTransfer->getPriceTypeName(),
            static::HEADER_STORE => $moneyValueTransfer->getStore()->getName(),
            static::HEADER_CURRENCY => $moneyValueTransfer->getCurrency()->getCode(),
            static::HEADER_NET_PRICE => $moneyValueTransfer->getNetAmount(),
            static::HEADER_GROSS_PRICE => $moneyValueTransfer->getGrossAmount(),
            static::HEADER_FROM => $dateFrom->format(static::PATTERN_DATE_TIME),
            static::HEADER_TO => $dateTo->format(static::PATTERN_DATE_TIME),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string|null
     */
    protected function getSkuProductAsbtract(PriceProductTransfer $priceProductTransfer): ?string
    {
        if ($priceProductTransfer->getSkuProduct() === null) {
            return $priceProductTransfer->getSkuProductAbstract();
        }

        return null;
    }
}
