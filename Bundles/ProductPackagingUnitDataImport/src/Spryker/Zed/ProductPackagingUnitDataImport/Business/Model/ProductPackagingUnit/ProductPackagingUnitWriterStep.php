<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\ProductPackagingUnit;

use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitAmount;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\ProductPackagingUnitEvents;
use Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\DataSet\ProductPackagingUnitDataSetInterface;

class ProductPackagingUnitWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const HEAP_LIMIT = 500;
    protected const PRODUCT_CONCRETE_ID = 'PRODUCT_CONCRETE_ID';
    protected const PRODUCT_ABSTRACT_ID = 'PRODUCT_ABSTRACT_ID';

    /**
     * @uses \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReader::PRODUCT_ABSTRACT_STORAGE_DEFAULT_VALUES
     */
    protected const DEFAULT_AMOUNT_DEFAULT_VALUE = 1;

    /**
     * @var int[] Keys are product packaging unit type names.
     */
    protected static $idProductPackagingUnitTypeHeap = [];

    /**
     * @var array Keys are product SKUs, values are a set of product abstract ID and product concrete ID.
     */
    protected static $productHeap = [];

    /**
     * @var int
     */
    protected static $productHeapSize = 0;

    /**
     * @var bool[] Keys are product SKUs, values boolean representing if this product have a MeasurementSalesUnit or not.
     */
    protected static $productMeasurementSalesUnitHeap = [];

    /**
     * @var int
     */
    protected static $productMeasurementSalesUnitHeapSize = 0;

    public function __construct()
    {
        $this->initIdProductPackagingUnitTypeHeap();
    }

    /**
     * @module Product
     * @module ProductPackagingUnit
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assertHaveProductMeasurementSalesUnit($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_CONCRETE_SKU]);
        $dataSet = $this->normalizeDataSet($dataSet);
        $productPackagingUnitTypeId = $this->getIdProductPackagingUnitTypeByName($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_TYPE_NAME]);
        $productConcreteId = $this->getIdProductBySku($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_CONCRETE_SKU]);

        $productPackagingUnitEntity = $this->getProductPackagingUnitQuery()
            ->filterByFkProduct($productConcreteId)
            ->findOneOrCreate();

        $this->persistLeadProduct($dataSet, $productConcreteId);

        $productPackagingUnitEntity
            ->setHasLeadProduct($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_HAS_LEAD_PRODUCT])
            ->setFkProductPackagingUnitType($productPackagingUnitTypeId);

        $productPackagingUnitEntity->save();

        if ($this->hasAmount($dataSet)) {
            $this->persistAmount($dataSet, $productPackagingUnitEntity);
        }

        $this->addPublishEvents(ProductPackagingUnitEvents::PRODUCT_ABSTRACT_PACKAGING_PUBLISH, $this->getIdProductAbstractByProductSku($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_CONCRETE_SKU]));
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param int $productConcreteId
     *
     * @return void
     */
    protected function persistLeadProduct(DataSetInterface $dataSet, int $productConcreteId): void
    {
        if (!$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_LEAD_PRODUCT]) {
            return;
        }

        $productPackagingLeadProductEntity = $this->getProductPackagingLeadProductQuery()
            ->filterByFkProductAbstract($this->getIdProductAbstractByProductSku($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_CONCRETE_SKU]))
            ->findOneOrCreate();

        $productPackagingLeadProductEntity
            ->setFkProduct($productConcreteId)
            ->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function normalizeDataSet(DataSetInterface $dataSet): DataSetInterface
    {
        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_LEAD_PRODUCT] = (bool)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_LEAD_PRODUCT];
        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_HAS_LEAD_PRODUCT] = (bool)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_HAS_LEAD_PRODUCT];
        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_VARIABLE] = (bool)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_VARIABLE];

        if ($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_LEAD_PRODUCT]) {
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_HAS_LEAD_PRODUCT] = false;
        }

        $dataSet = $this->normalizeAmount($dataSet);

        return $dataSet;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function normalizeAmount(DataSetInterface $dataSet): DataSetInterface
    {
        $isVariable = $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_VARIABLE];
        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_VARIABLE] = $isVariable;

        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_DEFAULT_AMOUNT] = (int)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_DEFAULT_AMOUNT];
        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MIN] = (int)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MIN];
        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MAX] = (int)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MAX];
        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_INTERVAL] = (int)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_INTERVAL];

        if ($isVariable && $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_INTERVAL] === 0) {
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_INTERVAL] = 1;
        }

        if ($isVariable && $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MIN] === 0) {
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MIN] = $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_INTERVAL];
        }

        if (!$isVariable) {
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MIN] = null;
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MAX] = null;
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_INTERVAL] = null;
        }

        return $dataSet;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit $productPackagingUnitEntity
     *
     * @return void
     */
    protected function persistAmount(DataSetInterface $dataSet, SpyProductPackagingUnit $productPackagingUnitEntity): void
    {
        $productPackagingUnitAmountEntity = $productPackagingUnitEntity->getSpyProductPackagingUnitAmounts()->getFirst();

        if ($productPackagingUnitAmountEntity === null) {
            $productPackagingUnitAmountEntity = new SpyProductPackagingUnitAmount();
            $productPackagingUnitAmountEntity
                ->setFkProductPackagingUnit($productPackagingUnitEntity->getIdProductPackagingUnit());
        }

        $productPackagingUnitAmountEntity
            ->setIsVariable($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_VARIABLE])
            ->setDefaultAmount($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_DEFAULT_AMOUNT])
            ->setAmountMin($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MIN])
            ->setAmountMax($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MAX])
            ->setAmountInterval($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_INTERVAL])
            ->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function hasAmount(DataSetInterface $dataSet): bool
    {
        if ($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_LEAD_PRODUCT]) {
            return false;
        }

        if ($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_VARIABLE]) {
            return true;
        }

        if (empty($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_DEFAULT_AMOUNT]) ||
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_DEFAULT_AMOUNT] === static::DEFAULT_AMOUNT_DEFAULT_VALUE
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductPackagingUnitTypeByName(string $name): int
    {
        if (isset(static::$idProductPackagingUnitTypeHeap[$name])) {
            return static::$idProductPackagingUnitTypeHeap[$name];
        }

        throw new EntityNotFoundException(sprintf("Product packaging unit type '%s' was not found", $name));
    }

    /**
     * @param string $productSku
     *
     * @return int
     */
    protected function getIdProductBySku(string $productSku): int
    {
        $this->addProductToProductHeapBySku($productSku);

        return static::$productHeap[$productSku][static::PRODUCT_CONCRETE_ID];
    }

    /**
     * @param string $productSku
     *
     * @return int
     */
    protected function getIdProductAbstractByProductSku(string $productSku): int
    {
        $this->addProductToProductHeapBySku($productSku);

        return static::$productHeap[$productSku][static::PRODUCT_ABSTRACT_ID];
    }

    /**
     * @param string $productSku
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function assertHaveProductMeasurementSalesUnit(string $productSku): void
    {
        $this->addProductMeasurementSalesUnitCheckToHeap($productSku);

        if (static::$productMeasurementSalesUnitHeap[$productSku]) {
            return;
        }

        throw new EntityNotFoundException(sprintf("Product measurement sales unit was not found for SKU '%s'", $productSku));
    }

    /**
     * @param string $productSku
     *
     * @return void
     */
    protected function addProductMeasurementSalesUnitCheckToHeap(string $productSku): void
    {
        if (isset(static::$productMeasurementSalesUnitHeap[$productSku])) {
            return;
        }

        if (static::$productMeasurementSalesUnitHeapSize >= static::HEAP_LIMIT) {
            $this->clearProductMeasurementSalesUnitHeap();
        }

        $productConcreteId = $this->getIdProductBySku($productSku);

        static::$productMeasurementSalesUnitHeapSize++;
        static::$productMeasurementSalesUnitHeap[$productSku] = $this->getProductMeasurementSalesUnitQuery()
            ->filterByFkProduct($productConcreteId)
            ->exists();
    }

    /**
     * @param string $productSku
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function addProductToProductHeapBySku(string $productSku): void
    {
        if (isset(static::$productHeap[$productSku])) {
            return;
        }

        $productEntity = $this->getProductQuery()
            ->filterBySku($productSku)
            ->findOne();

        if ($productEntity === null) {
            throw new EntityNotFoundException(sprintf("Product concrete with SKU '%s' was not found", $productSku));
        }

        $this->addProductToProductHeap($productEntity);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return void
     */
    protected function addProductToProductHeap(SpyProduct $productEntity): void
    {
        if (static::$productHeapSize >= static::HEAP_LIMIT) {
            $this->clearProductHeap();
        }

        static::$productHeapSize++;
        static::$productHeap[$productEntity->getSku()] = [
            static::PRODUCT_CONCRETE_ID => $productEntity->getIdProduct(),
            static::PRODUCT_ABSTRACT_ID => $productEntity->getFkProductAbstract(),
        ];
    }

    /**
     * @return void
     */
    protected function clearProductHeap(): void
    {
        static::$productHeapSize = 0;
        static::$productHeap = [];
    }

    /**
     * @return void
     */
    protected function clearProductMeasurementSalesUnitHeap(): void
    {
        static::$productMeasurementSalesUnitHeapSize = 0;
        static::$productMeasurementSalesUnitHeap = [];
    }

    /**
     * @return void
     */
    protected function initIdProductPackagingUnitTypeHeap(): void
    {
        $productPackagingUnitTypeEntities = $this->getProductPackagingUnitTypeQuery()->find();

        foreach ($productPackagingUnitTypeEntities as $packagingUnitTypeEntity) {
            static::$idProductPackagingUnitTypeHeap[$packagingUnitTypeEntity->getName()] = $packagingUnitTypeEntity->getIdProductPackagingUnitType();
        }

        unset($productPackagingUnitTypeEntities);
    }

    /**
     * @module ProductPackagingUnit
     *
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery
     */
    protected function getProductPackagingLeadProductQuery(): SpyProductPackagingLeadProductQuery
    {
        return SpyProductPackagingLeadProductQuery::create();
    }

    /**
     * @module Product
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function getProductQuery(): SpyProductQuery
    {
        return SpyProductQuery::create();
    }

    /**
     * @module ProductPackagingUnit
     *
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }

    /**
     * @module ProductPackagingUnit
     *
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    protected function getProductPackagingUnitTypeQuery(): SpyProductPackagingUnitTypeQuery
    {
        return SpyProductPackagingUnitTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery
     */
    protected function getProductMeasurementSalesUnitQuery(): SpyProductMeasurementSalesUnitQuery
    {
        return SpyProductMeasurementSalesUnitQuery::create();
    }
}
