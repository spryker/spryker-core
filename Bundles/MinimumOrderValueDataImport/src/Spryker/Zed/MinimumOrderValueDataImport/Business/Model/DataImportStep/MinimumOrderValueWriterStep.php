<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MinimumOrderValueDataImport\Business\Model\DataImportStep;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MinimumOrderValueDataImport\Business\Model\DataSet\MinimumOrderValueDataSetInterface;
use Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToCurrencyFacadeInterface;
use Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToStoreFacadeInterface;

class MinimumOrderValueWriterStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToMinimumOrderValueFacadeInterface
     */
    protected $minimumOrderValueFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected $storesHeap = [];

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected $currenciesHeap = [];

    /**
     * @param \Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToMinimumOrderValueFacadeInterface $minimumOrderValueFacade
     * @param \Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        MinimumOrderValueDataImportToMinimumOrderValueFacadeInterface $minimumOrderValueFacade,
        MinimumOrderValueDataImportToStoreFacadeInterface $storeFacade,
        MinimumOrderValueDataImportToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->minimumOrderValueFacade = $minimumOrderValueFacade;
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
        $storeTransfer = $this->findStoreByName($dataSet[MinimumOrderValueDataSetInterface::STORE]);
        if (!$storeTransfer) {
            return;
        }

        $currencyTransfer = $this->findCurrencyByCode($dataSet[MinimumOrderValueDataSetInterface::CURRENCY]);
        if (!$currencyTransfer) {
            return;
        }

        if ($dataSet[MinimumOrderValueDataSetInterface::STRATEGY] && $dataSet[MinimumOrderValueDataSetInterface::THRESHOLD]) {
            $globalMinimumOrderValueTransfer = $this->createGlobalMinimumOrderValueTransfer(
                $dataSet[MinimumOrderValueDataSetInterface::STRATEGY],
                $storeTransfer,
                $currencyTransfer,
                (int)$dataSet[MinimumOrderValueDataSetInterface::THRESHOLD],
                (int)$dataSet[MinimumOrderValueDataSetInterface::FEE]
            );

            $this->minimumOrderValueFacade->setGlobalThreshold($globalMinimumOrderValueTransfer);
        }
    }

    /**
     * @param string $strategyKey
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    protected function createGlobalMinimumOrderValueTransfer(
        string $strategyKey,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): GlobalMinimumOrderValueTransfer {
        return (new GlobalMinimumOrderValueTransfer())
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setMinimumOrderValue(
                (new MinimumOrderValueTransfer())
                    ->setValue($thresholdValue)
                    ->setFee($fee)
                    ->setMinimumOrderValueType(
                        (new MinimumOrderValueTypeTransfer())
                            ->setKey($strategyKey)
                    )
            );
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function findStoreByName(string $storeName): StoreTransfer
    {
        if (!isset($this->storesHeap[$storeName])) {
            $this->storesHeap[$storeName] = $this->storeFacade->getStoreByName($storeName);
        }

        return $this->storesHeap[$storeName];
    }

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function findCurrencyByCode(string $isoCode): CurrencyTransfer
    {
        if (!isset($this->currenciesHeap[$isoCode])) {
            $this->currenciesHeap[$isoCode] = $this->currencyFacade->fromIsoCode($isoCode);
        }

        return $this->currenciesHeap[$isoCode];
    }
}
