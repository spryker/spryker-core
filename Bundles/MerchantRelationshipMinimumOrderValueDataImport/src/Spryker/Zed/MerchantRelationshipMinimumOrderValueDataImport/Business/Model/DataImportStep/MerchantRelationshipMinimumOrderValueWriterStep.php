<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Business\Model\DataImportStep;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
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
    protected const MERCHANT_RELATIONSHIPS_HEAP_LIMIT = 200;

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
     * @var \Generated\Shared\Transfer\MerchantRelationshipTransfer[] Keys are merchant relationship keys.
     */
    protected $merchantRelationshipsHeap = [];

    /**
     * @var int
     */
    protected $merchantRelationshipsHeapSize = 0;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[] Keys are store names.
     */
    protected $storesHeap = [];

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer[] Keys are currency codes.
     */
    protected $currenciesHeap = [];

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
        $merchantRelationshipTransfer = $this->findMerchantRelationshipByKey(
            $dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::COLUMN_MERCHANT_RELATIONSHIP_KEY]
        );
        if ($merchantRelationshipTransfer === null) {
            return;
        }

        $storeTransfer = $this->findStoreByName($dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::COLUMN_STORE]);
        if ($storeTransfer === null) {
            return;
        }

        $currencyTransfer = $this->findCurrencyByCode($dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::COLUMN_CURRENCY]);
        if ($currencyTransfer === null) {
            return;
        }

        if ($dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::COLUMN_MINIMUM_ORDER_VALUE_TYPE_KEY] && $dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::COLUMN_THRESHOLD]) {
            $merchantRelationshipMinimumOrderValueTransfer = $this->createMerchantRelationshipMinimumOrderValueTransfer(
                $dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::COLUMN_MINIMUM_ORDER_VALUE_TYPE_KEY],
                $merchantRelationshipTransfer,
                $storeTransfer,
                $currencyTransfer,
                (int)$dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::COLUMN_THRESHOLD],
                (int)$dataSet[MerchantRelationshipMinimumOrderValueDataSetInterface::COLUMN_FEE]
            );

            $this->merchantRelationshipMinimumOrderValueFacade->saveMerchantRelationshipMinimumOrderValue(
                $merchantRelationshipMinimumOrderValueTransfer
            );
        }
    }

    /**
     * @param string $minimumOrderValueTypeKey
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    protected function createMerchantRelationshipMinimumOrderValueTransfer(
        string $minimumOrderValueTypeKey,
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
            ->setThreshold(
                (new MinimumOrderValueThresholdTransfer())
                    ->setValue($thresholdValue)
                    ->setFee($fee)
                    ->setMinimumOrderValueType(
                        (new MinimumOrderValueTypeTransfer())
                            ->setKey($minimumOrderValueTypeKey)
                    )
            );
    }

    /**
     * @param string $merchantRelationshipKey
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    protected function findMerchantRelationshipByKey(string $merchantRelationshipKey): ?MerchantRelationshipTransfer
    {
        if ($this->merchantRelationshipsHeapSize > static::MERCHANT_RELATIONSHIPS_HEAP_LIMIT) {
            $this->merchantRelationshipsHeapSize = 0;
            $this->merchantRelationshipsHeap = [];
        }

        if (!isset($this->merchantRelationshipsHeap[$merchantRelationshipKey])) {
            $merchantRelationshipTransfer = $this->merchantRelationshipFacade->findMerchantRelationshipByKey(
                (new MerchantRelationshipTransfer())->setMerchantRelationshipKey($merchantRelationshipKey)
            );

            if (!$merchantRelationshipTransfer) {
                return null;
            }

            $this->merchantRelationshipsHeap[$merchantRelationshipKey] = $merchantRelationshipTransfer;
            $this->merchantRelationshipsHeapSize++;
        }

        return $this->merchantRelationshipsHeap[$merchantRelationshipKey];
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
