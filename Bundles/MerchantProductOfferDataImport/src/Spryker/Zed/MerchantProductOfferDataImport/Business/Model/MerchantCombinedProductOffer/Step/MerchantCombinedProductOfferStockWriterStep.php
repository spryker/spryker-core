<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAfterExecuteInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait\IsNewProductOfferGetterTrait;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait\ProductOfferGetterTrait;
use Spryker\Zed\MerchantProductOfferDataImport\Dependency\MerchantProductOfferDataImportEvents;

class MerchantCombinedProductOfferStockWriterStep implements DataImportStepInterface, DataImportStepAfterExecuteInterface
{
    use ProductOfferGetterTrait;
    use IsNewProductOfferGetterTrait;

    /**
     * @var string
     */
    protected const KEY_QUANTITY = 'quantity';

    /**
     * @var string
     */
    protected const KEY_IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_WAREHOUSE_ACCESS_RESTRICTED = 'Warehouses can only be accessed by the merchants who own them.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_QUANTITY_IS_REQUIRED_ON_CREATE = 'Quantity is required for new product offer.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STOCK_QUANTITY_MUST_BE_NUMERIC = 'Stock quantity must be numeric.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STOCK_QUANTITY_MUST_BE_POSITIVE = 'Stock quantity must be positive.';

    /**
     * @var array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected array $productOfferStockEventTransfers = [];

    public function __construct(protected DataImportToEventFacadeInterface $eventFacade)
    {
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantStocks = $this->getMerchantStocks($dataSet);

        $productOfferEntity = $this->getProductOfferFromDataSet($dataSet);
        $productOfferStocks = $this->getProductOfferStocks($dataSet);

        if (!$productOfferStocks && $this->getIsNewProductOffer($dataSet)) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_QUANTITY_IS_REQUIRED_ON_CREATE),
            );
        }

        foreach ($productOfferStocks as $warehouseName => $attributes) {
            $this->validateMerchantHasAccessToWarehouse($warehouseName, $merchantStocks);

            $this->persistProductOfferStock(
                $productOfferEntity->getIdProductOffer(),
                $merchantStocks[$warehouseName],
                $attributes,
            );
        }
    }

    public function afterExecute(): void
    {
        $this->eventFacade->triggerBulk(
            MerchantProductOfferDataImportEvents::ENTITY_SPY_PRODUCT_OFFER_STOCK_PUBLISH,
            $this->productOfferStockEventTransfers,
        );
        $this->productOfferStockEventTransfers = [];
    }

    /**
     * @param array<string, int> $merchantStocks
     *
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function validateMerchantHasAccessToWarehouse(
        string $warehouseName,
        array $merchantStocks
    ): void {
        if (!isset($merchantStocks[$warehouseName])) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_WAREHOUSE_ACCESS_RESTRICTED),
            );
        }
    }

    /**
     * @param array<string, mixed> $attributes
     *
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function persistProductOfferStock(int $idProductOffer, int $idStock, array $attributes): void
    {
        $productOfferStockEntity = SpyProductOfferStockQuery::create()
            ->filterByFkStock($idStock)
            ->filterByFkProductOffer($idProductOffer)
            ->findOneOrCreate();

        $this->setQuantity($productOfferStockEntity, $attributes);
        $this->setIsNeverOutOfStock($productOfferStockEntity, $attributes);

        if ($productOfferStockEntity->isNew() && !$productOfferStockEntity->getQuantity()) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_QUANTITY_IS_REQUIRED_ON_CREATE),
            );
        }

        if ($productOfferStockEntity->isNew() && !$productOfferStockEntity->getIsNeverOutOfStock()) {
            $productOfferStockEntity->setIsNeverOutOfStock(false);
        }

        if (!$productOfferStockEntity->isNew() || $productOfferStockEntity->isModified()) {
            $productOfferStockEntity->save();

            $this->addProductOfferStockPublishEvent($productOfferStockEntity);
        }
    }

    /**
     * @param array<string, mixed> $attributes
     *
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function setQuantity(SpyProductOfferStock $productOfferStockEntity, array $attributes): void
    {
        if (!isset($attributes[static::KEY_QUANTITY])) {
            return;
        }

        /** @var int|false $quantity */
        $quantity = filter_var($attributes[static::KEY_QUANTITY], FILTER_VALIDATE_INT);

        if (!$quantity) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_STOCK_QUANTITY_MUST_BE_NUMERIC),
            );
        }

        if ($quantity < 0) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_STOCK_QUANTITY_MUST_BE_POSITIVE),
            );
        }

        $productOfferStockEntity->setQuantity((string)$quantity);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    protected function setIsNeverOutOfStock(SpyProductOfferStock $productOfferStockEntity, array $attributes): void
    {
        if (!isset($attributes[static::KEY_IS_NEVER_OUT_OF_STOCK])) {
            return;
        }

        $isNeverOutOfStock = filter_var($attributes[static::KEY_IS_NEVER_OUT_OF_STOCK], FILTER_VALIDATE_BOOLEAN);

        $productOfferStockEntity->setIsNeverOutOfStock($isNeverOutOfStock);
    }

    protected function addProductOfferStockPublishEvent(SpyProductOfferStock $productOfferStockEntity): void
    {
        $eventEntityTransfer = (new EventEntityTransfer())
            ->setId($productOfferStockEntity->getFkProductOffer())
            ->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferStockEntity->getSpyProductOffer()->getProductOfferReference(),
                SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferStockEntity->getSpyProductOffer()->getConcreteSku(),
            ]);

        $this->productOfferStockEventTransfers[] = $eventEntityTransfer;
    }

    /**
     * @return array<string, int>
     */
    protected function getMerchantStocks(DataSetInterface $dataSet): array
    {
        return $dataSet[CombinedProductOfferDataSetInterface::DATA_MERCHANT_STOCKS] ?? [];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function getProductOfferStocks(DataSetInterface $dataSet): array
    {
        return $dataSet[CombinedProductOfferDataSetInterface::DATA_PRODUCT_OFFER_STOCKS] ?? [];
    }
}
