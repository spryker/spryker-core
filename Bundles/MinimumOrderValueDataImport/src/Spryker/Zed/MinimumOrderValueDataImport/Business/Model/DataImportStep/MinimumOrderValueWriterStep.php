<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueDataImport\Business\Model\DataImportStep;

use Generated\Shared\Transfer\CurrencyTransfer;
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
        $storeTransfer = $this->storeFacade->getStoreByName($dataSet[MinimumOrderValueDataSetInterface::STORE]);
        if (!$storeTransfer) {
            return;
        }

        $currencyTransfer = $this->currencyFacade->fromIsoCode($dataSet[MinimumOrderValueDataSetInterface::CURRENCY]);
        if (!$currencyTransfer) {
            return;
        }

        if ($dataSet[MinimumOrderValueDataSetInterface::STRATEGY] && $dataSet[MinimumOrderValueDataSetInterface::THRESHOLD]) {
            $minimumOrderValueTransfer = $this->createMinimumOrderValueTransfer(
                $dataSet[MinimumOrderValueDataSetInterface::STRATEGY],
                $storeTransfer,
                $currencyTransfer,
                (int)$dataSet[MinimumOrderValueDataSetInterface::THRESHOLD],
                (int)$dataSet[MinimumOrderValueDataSetInterface::FEE]
            );

            $this->minimumOrderValueFacade->setStoreThreshold($minimumOrderValueTransfer);
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
            ->setValue($thresholdValue)
            ->setFee($fee)
            ->setMinimumOrderValueType(
                (new MinimumOrderValueTypeTransfer())
                    ->setKey($strategyKey)
            );
    }
}
