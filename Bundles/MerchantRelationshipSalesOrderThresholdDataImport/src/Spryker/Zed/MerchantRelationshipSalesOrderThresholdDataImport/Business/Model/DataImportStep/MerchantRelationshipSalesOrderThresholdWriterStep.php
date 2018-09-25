<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Business\Model\DataImportStep;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Business\Model\DataSet\MerchantRelationshipSalesOrderThresholdDataSetInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToCurrencyFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipSalesOrderThresholdFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeInterface;
use Throwable;

class MerchantRelationshipSalesOrderThresholdWriterStep implements DataImportStepInterface
{
    protected const MERCHANT_RELATIONSHIPS_HEAP_LIMIT = 200;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipSalesOrderThresholdFacadeInterface
     */
    protected $merchantRelationshipSalesOrderThresholdFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToCurrencyFacadeInterface
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
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipSalesOrderThresholdFacadeInterface $merchantRelationshipSalesOrderThresholdFacade
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipSalesOrderThresholdFacadeInterface $merchantRelationshipSalesOrderThresholdFacade,
        MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeInterface $storeFacade,
        MerchantRelationshipSalesOrderThresholdDataImportToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->merchantRelationshipSalesOrderThresholdFacade = $merchantRelationshipSalesOrderThresholdFacade;
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
        $storeTransfer = $this->getStoreByName($dataSet[MerchantRelationshipSalesOrderThresholdDataSetInterface::COLUMN_STORE]);
        $currencyTransfer = $this->getCurrencyByCode($dataSet[MerchantRelationshipSalesOrderThresholdDataSetInterface::COLUMN_CURRENCY]);
        $merchantRelationshipTransfer = $this->getMerchantRelationshipByKey(
            $dataSet[MerchantRelationshipSalesOrderThresholdDataSetInterface::COLUMN_MERCHANT_RELATIONSHIP_KEY]
        );

        if ($dataSet[MerchantRelationshipSalesOrderThresholdDataSetInterface::COLUMN_SALES_ORDER_THRESHOLD_TYPE_KEY] && $dataSet[MerchantRelationshipSalesOrderThresholdDataSetInterface::COLUMN_THRESHOLD]) {
            $merchantRelationshipSalesOrderThresholdTransfer = $this->createMerchantRelationshipSalesOrderThresholdTransfer(
                $dataSet[MerchantRelationshipSalesOrderThresholdDataSetInterface::COLUMN_SALES_ORDER_THRESHOLD_TYPE_KEY],
                $merchantRelationshipTransfer,
                $storeTransfer,
                $currencyTransfer,
                (int)$dataSet[MerchantRelationshipSalesOrderThresholdDataSetInterface::COLUMN_THRESHOLD],
                (int)$dataSet[MerchantRelationshipSalesOrderThresholdDataSetInterface::COLUMN_FEE],
                $dataSet[MerchantRelationshipSalesOrderThresholdDataSetInterface::COLUMN_MESSAGE_GLOSSARY_KEY]
            );

            $this->merchantRelationshipSalesOrderThresholdFacade->saveMerchantRelationshipSalesOrderThreshold(
                $merchantRelationshipSalesOrderThresholdTransfer
            );
        }
    }

    /**
     * @param string $salesOrderThresholdTypeKey
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     * @param string|null $glossaryKey
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    protected function createMerchantRelationshipSalesOrderThresholdTransfer(
        string $salesOrderThresholdTypeKey,
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null,
        ?string $glossaryKey = null
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        return (new MerchantRelationshipSalesOrderThresholdTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer)
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
     * @param string $merchantRelationshipKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function getMerchantRelationshipByKey(string $merchantRelationshipKey): MerchantRelationshipTransfer
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
                throw new EntityNotFoundException(sprintf('Merchant relationship not found: %s', $merchantRelationshipKey));
            }

            $this->merchantRelationshipsHeap[$merchantRelationshipKey] = $merchantRelationshipTransfer;
            $this->merchantRelationshipsHeapSize++;
        }

        return $this->merchantRelationshipsHeap[$merchantRelationshipKey];
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
            } catch (Throwable $throwable) {
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
