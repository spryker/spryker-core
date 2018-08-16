<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MinimumOrderValueDataImport\Business\Model\DataImportStep;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
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
     * @var \Generated\Shared\Transfer\StoreTransfer[] Keys are store names.
     */
    protected $storesHeap = [];

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer[] Keys are currency codes.
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
        $storeTransfer = $this->findStoreByName($dataSet[MinimumOrderValueDataSetInterface::COLUMN_STORE]);
        if ($storeTransfer === null) {
            return;
        }

        $currencyTransfer = $this->findCurrencyByCode($dataSet[MinimumOrderValueDataSetInterface::COLUMN_CURRENCY]);
        if ($currencyTransfer === null) {
            return;
        }

        if ($dataSet[MinimumOrderValueDataSetInterface::COLUMN_MINIMUM_ORDER_VALUE_TYPE_KEY] && $dataSet[MinimumOrderValueDataSetInterface::COLUMN_THRESHOLD]) {
            $minimumOrderValueTValueTransfer = $this->createMinimumOrderValueTransfer(
                $dataSet[MinimumOrderValueDataSetInterface::COLUMN_MINIMUM_ORDER_VALUE_TYPE_KEY],
                $storeTransfer,
                $currencyTransfer,
                (int)$dataSet[MinimumOrderValueDataSetInterface::COLUMN_THRESHOLD],
                (int)$dataSet[MinimumOrderValueDataSetInterface::COLUMN_FEE]
            );

            $this->minimumOrderValueFacade->saveMinimumOrderValue($minimumOrderValueTValueTransfer);
        }
    }

    /**
     * @param string $strategyKey
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    protected function createMinimumOrderValueTransfer(
        string $strategyKey,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MinimumOrderValueTransfer {
        return (new MinimumOrderValueTransfer())
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setThreshold(
                (new MinimumOrderValueThresholdTransfer())
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
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    protected function findStoreByName(string $storeName): ?StoreTransfer
    {
        if (!isset($this->storesHeap[$storeName])) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
            if ($storeTransfer === null) {
                return null;
            }

            $this->storesHeap[$storeName] = $storeTransfer;
        }

        return $this->storesHeap[$storeName];
    }

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    protected function findCurrencyByCode(string $isoCode): ?CurrencyTransfer
    {
        if (!isset($this->currenciesHeap[$isoCode])) {
            $currencyTransfer = $this->currencyFacade->fromIsoCode($isoCode);
            if ($currencyTransfer === null) {
                return null;
            }

            $this->currenciesHeap[$isoCode] = $currencyTransfer;
        }

        return $this->currenciesHeap[$isoCode];
    }
}
