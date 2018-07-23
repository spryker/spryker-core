<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\ProductPackagingUnit;

use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitAmount;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\ProductPackagingUnitEvents;
use Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\DataSet\ProductPackagingUnitDataSetInterface;

class ProductPackagingUnitWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const PRODUCT_HEAP_LIMIT = 500;
    protected const PRODUCT_CONCRETE_ID = 'PRODUCT_CONCRETE_ID';
    protected const PRODUCT_ABSTRACT_ID = 'PRODUCT_ABSTRACT_ID';

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
     * ProductPackagingUnitWriterStep constructor.
     */
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
        $dataSet = $this->normalizeDataSet($dataSet);

        $productPackagingUnitEntity = $this->getProductPackagingUnitQuery()
            ->useProductQuery()
                ->filterBySku($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_CONCRETE_SKU])
            ->endUse()
            ->useProductPackagingUnitTypeQuery(null, Criteria::LEFT_JOIN)
                ->filterByName($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_TYPE_NAME])
            ->endUse()
            ->leftJoinWithSpyProductPackagingUnitAmount()
            ->find()
            ->getFirst();

        if ($productPackagingUnitEntity === null) {
            $productPackagingUnitEntity = new SpyProductPackagingUnit();
        }

        $productConcreteId = $this->getIdProductBySku($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_CONCRETE_SKU]);
        $this->persistLeadProduct($dataSet, $productConcreteId);

        $productPackagingUnitEntity
            ->setHasLeadProduct($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_HAS_LEAD_PRODUCT]);

        if ($productPackagingUnitEntity->isNew()) {
            $productPackagingUnitEntity
                ->setFkProduct($productConcreteId)
                ->setFkProductPackagingUnitType($this->getIdProductPackagingUnitTypeByName($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_TYPE_NAME]));
        }

        $productPackagingUnitEntity->save();

        $this->persistAmount($dataSet, $productPackagingUnitEntity);

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
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     */
    protected function normalizeDataSet(DataSetInterface $dataSet): DataSetInterface
    {
        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_LEAD_PRODUCT] = (bool)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_LEAD_PRODUCT];
        $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_HAS_LEAD_PRODUCT] = (bool)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_HAS_LEAD_PRODUCT];

        if ($dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_LEAD_PRODUCT]) {
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_HAS_LEAD_PRODUCT] = false;
        }

        $dataSet = $this->normalizeAmount($dataSet);

        return $dataSet;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     */
    protected function normalizeAmount(DataSetInterface $dataSet): DataSetInterface
    {
        $isVariable = (bool)$dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_VARIABLE];
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
        $haveAmount = $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_DEFAULT_AMOUNT] > 1 ||
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MIN] > 0 ||
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_MAX] > 0 ||
            $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_AMOUNT_INTERVAL] > 0;

        if (!$haveAmount || $dataSet[ProductPackagingUnitDataSetInterface::COLUMN_IS_LEAD_PRODUCT]) {
            return;
        }

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
        if (static::$productHeapSize >= static::PRODUCT_HEAP_LIMIT) {
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
    protected function clearProductHeap()
    {
        static::$productHeapSize = 0;
        static::$productHeap = [];
    }

    /**
     * @return void
     */
    protected function initIdProductPackagingUnitTypeHeap()
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
}
