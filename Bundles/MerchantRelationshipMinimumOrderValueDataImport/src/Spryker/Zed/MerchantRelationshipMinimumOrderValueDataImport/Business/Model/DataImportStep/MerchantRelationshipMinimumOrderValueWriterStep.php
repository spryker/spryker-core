<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Business\Model\DataImportStep;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Business\Model\DataSet\MerchantRelationshipMinimumOrderValueDataSetInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToCurrencyFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipMinimumOrderValueFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToStoreFacadeInterface;

class MerchantRelationshipMinimumOrderValueWriterStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipMinimumOrderValueFacadeInterface
     */
    protected $merchantRelationshipMinimumOrderValueFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipMinimumOrderValueFacadeInterface $merchantRelationshipMinimumOrderValueFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipMinimumOrderValueFacadeInterface $merchantRelationshipMinimumOrderValueFacade,
        MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        MerchantRelationshipMinimumOrderValueDataImportToStoreFacadeInterface $storeFacade,
        MerchantRelationshipMinimumOrderValueDataImportToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->merchantRelationshipMinimumOrderValueFacade = $merchantRelationshipMinimumOrderValueFacade;
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
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
        $merchantRelationshipTransfer = $this->merchantRelationshipFacade->getMerchantRelationshipByKey(
            (new MerchantRelationshipTransfer())
                ->setMerchantRelationshipKey($dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::MERCHANT_RELATIONSHIP_KEY])
        );

        $storeTransfer = $this->storeFacade->getStoreByName($dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::STORE]);
        if (!$storeTransfer) {
            return;
        }

        $currencyTransfer = $this->currencyFacade->fromIsoCode($dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::CURRENCY]);
        if (!$currencyTransfer) {
            return;
        }

        if ($dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::STRATEGY] && $dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::THRESHOLD]) {
            $merchantRelationshipMinimumOrderValueTransfer = $this->createMerchantRelationshipMinimumOrderValueTransfer(
                $dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::STRATEGY],
                $merchantRelationshipTransfer,
                $storeTransfer,
                $currencyTransfer,
                (int)$dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::THRESHOLD],
                (int)$dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::FEE]
            );

            $this->merchantRelationshipMinimumOrderValueFacade->setMerchantRelationshipThreshold(
                $merchantRelationshipMinimumOrderValueTransfer
            );
        }
    }

    /**
     * @param string $strategyKey
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    protected function createMerchantRelationshipMinimumOrderValueTransfer(
        string $strategyKey,
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MerchantRelationshipMinimumOrderValueTransfer {
        return (new MerchantRelationshipMinimumOrderValueTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer)
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
