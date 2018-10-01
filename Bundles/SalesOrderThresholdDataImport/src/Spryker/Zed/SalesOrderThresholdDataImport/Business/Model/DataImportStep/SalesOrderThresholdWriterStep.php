<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesOrderThresholdDataImport\Business\Model\DataImportStep;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\Business\Model\DataSet\SalesOrderThresholdDataSetInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToCurrencyFacadeInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToStoreFacadeInterface;
use Throwable;

class SalesOrderThresholdWriterStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface
     */
    protected $salesOrderThresholdFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[] Keys are store names.
     */
    protected $storesHeap = [];

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer[] Keys are currency codes.
     */
    protected $currenciesHeap = [];

    /**
     * @param \Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade
     * @param \Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade,
        SalesOrderThresholdDataImportToStoreFacadeInterface $storeFacade,
        SalesOrderThresholdDataImportToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->salesOrderThresholdFacade = $salesOrderThresholdFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeTransfer = $this->getStoreByName($dataSet[SalesOrderThresholdDataSetInterface::COLUMN_STORE]);
        $currencyTransfer = $this->getCurrencyByCode($dataSet[SalesOrderThresholdDataSetInterface::COLUMN_CURRENCY]);

        if ($dataSet[SalesOrderThresholdDataSetInterface::COLUMN_SALES_ORDER_THRESHOLD_TYPE_KEY] && $dataSet[SalesOrderThresholdDataSetInterface::COLUMN_THRESHOLD]) {
            $salesOrderThresholdTransfer = $this->createSalesOrderThresholdTransfer(
                $dataSet[SalesOrderThresholdDataSetInterface::COLUMN_SALES_ORDER_THRESHOLD_TYPE_KEY],
                $storeTransfer,
                $currencyTransfer,
                (int)$dataSet[SalesOrderThresholdDataSetInterface::COLUMN_THRESHOLD],
                (int)$dataSet[SalesOrderThresholdDataSetInterface::COLUMN_FEE],
                $dataSet[SalesOrderThresholdDataSetInterface::COLUMN_MESSAGE_GLOSSARY_KEY]
            );

            $this->salesOrderThresholdFacade->saveSalesOrderThreshold($salesOrderThresholdTransfer);
        }
    }

    /**
     * @param string $salesOrderThresholdTypeKey
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     * @param string|null $glossaryKey
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function createSalesOrderThresholdTransfer(
        string $salesOrderThresholdTypeKey,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null,
        ?string $glossaryKey = null
    ): SalesOrderThresholdTransfer {
        return (new SalesOrderThresholdTransfer())
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setSalesOrderThresholdValue(
                (new SalesOrderThresholdValueTransfer())
                    ->setThreshold($thresholdValue)
                    ->setFee($fee)
                    ->setMessageGlossaryKey($glossaryKey)
                    ->setSalesOrderThresholdType(
                        (new SalesOrderThresholdTypeTransfer())
                            ->setKey($salesOrderThresholdTypeKey)
                    )
            );
    }

    /**
     * @param string $storeName
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreByName(string $storeName): StoreTransfer
    {
        if (!isset($this->storesHeap[$storeName])) {
            try {
                $storeTransfer = $this->storeFacade->getStoreByName($storeName);

                $this->storesHeap[$storeName] = $storeTransfer;
            } catch (Throwable $t) {
                throw new EntityNotFoundException(sprintf('Store not found: %s', $storeName));
            }
        }

        return $this->storesHeap[$storeName];
    }

    /**
     * @param string $isoCode
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyByCode(string $isoCode): CurrencyTransfer
    {
        if (!isset($this->currenciesHeap[$isoCode])) {
            try {
                $currencyTransfer = $this->currencyFacade->fromIsoCode($isoCode);

                $this->currenciesHeap[$isoCode] = $currencyTransfer;
            } catch (Throwable $throwable) {
                throw new EntityNotFoundException(sprintf('Currency not found: %s', $isoCode));
            }
        }

        return $this->currenciesHeap[$isoCode];
    }
}
