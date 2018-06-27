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
    protected const PRODUCTS_HEAP_LIMIT = 500;
    protected const CONCRETE_PRODUCT_ID = 'CONCRETE_PRODUCT_ID';
    protected const ABSTRACT_PRODUCT_ID = 'ABSTRACT_PRODUCT_ID';

    /**
     * @var array
     */
    protected static $productPackagingUnitTypeHeap = [];

    /**
     * @var array
     */
    protected static $productsHeap = [];

    /**
     * @var int
     */
    protected static $productsHeapSize = 0;

    /**
     * ProductPackagingUnitWriterStep constructor.
     */
    public function __construct()
    {
        $this->initProductPackagingUnitTypeHeap();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet = $this->normalizeDataSet($dataSet);

        $productPackagingUnitEntity = $this->getProductPackagingUnitQuery()
            ->useProductQuery()
                ->filterBySku($dataSet[ProductPackagingUnitDataSetInterface::CONCRETE_SKU])
            ->endUse()
            ->useProductPackagingUnitTypeQuery(null, Criteria::LEFT_JOIN)
                ->filterByName($dataSet[ProductPackagingUnitDataSetInterface::TYPE_NAME])
            ->endUse()
            ->findOne();

        if ($productPackagingUnitEntity === null) {
            $productPackagingUnitEntity = new SpyProductPackagingUnit();
        }
        $productConcreteId = $this->getProductConcreteIdByConcreteSku($dataSet[ProductPackagingUnitDataSetInterface::CONCRETE_SKU]);
        $this->persistLeadProduct($dataSet, $productConcreteId);

        $productPackagingUnitEntity
            ->setHasLeadProduct($dataSet[ProductPackagingUnitDataSetInterface::HAS_LEAD_PRODUCT]);

        if ($productPackagingUnitEntity->isNew()) {
            $productPackagingUnitEntity
                ->setFkProduct($productConcreteId)
                ->setFkProductPackagingUnitType($this->getproductPackagingUnitTypeIdByname($dataSet[ProductPackagingUnitDataSetInterface::TYPE_NAME]));
        }

        $productPackagingUnitEntity->save();

        $this->persistAmount($dataSet, $productPackagingUnitEntity);

        $this->addPublishEvents(ProductPackagingUnitEvents::PRODUCT_PACKAGING_UNIT_PUBLISH, $this->getProductAbstractIdByConcreteSku($dataSet[ProductPackagingUnitDataSetInterface::CONCRETE_SKU]));
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param int $productConcreteId
     *
     * @return void
     */
    protected function persistLeadProduct(DataSetInterface $dataSet, int $productConcreteId): void
    {
        if (!$dataSet[ProductPackagingUnitDataSetInterface::IS_LEAD_PRODUCT]) {
            return;
        }

        $productPackagingLeadProductEntity = SpyProductPackagingLeadProductQuery::create()
            ->filterByFkProductAbstract($this->getProductAbstractIdByConcreteSku($dataSet[ProductPackagingUnitDataSetInterface::CONCRETE_SKU]))
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
        $dataSet[ProductPackagingUnitDataSetInterface::IS_LEAD_PRODUCT] = (bool)$dataSet[ProductPackagingUnitDataSetInterface::IS_LEAD_PRODUCT];
        $dataSet[ProductPackagingUnitDataSetInterface::HAS_LEAD_PRODUCT] = (bool)$dataSet[ProductPackagingUnitDataSetInterface::HAS_LEAD_PRODUCT];

        if ($dataSet[ProductPackagingUnitDataSetInterface::IS_LEAD_PRODUCT]) {
            $dataSet[ProductPackagingUnitDataSetInterface::HAS_LEAD_PRODUCT] = false;
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
        $isVariable = (bool)$dataSet[ProductPackagingUnitDataSetInterface::IS_VARIABLE];
        $dataSet[ProductPackagingUnitDataSetInterface::IS_VARIABLE] = $isVariable;

        $dataSet[ProductPackagingUnitDataSetInterface::DEFAULT_AMOUNT] = (int)$dataSet[ProductPackagingUnitDataSetInterface::DEFAULT_AMOUNT];
        $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MIN] = (int)$dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MIN];
        $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MAX] = (int)$dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MAX];
        $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_INTERVAL] = (int)$dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_INTERVAL];

        if ($isVariable && $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_INTERVAL] === 0) {
            $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_INTERVAL] = 1;
        }

        if ($isVariable && $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MIN] === 0) {
            $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MIN] = $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_INTERVAL];
        }

        if (!$isVariable) {
            $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MIN] = null;
            $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MAX] = null;
            $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_INTERVAL] = null;
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
        $haveAmount = $dataSet[ProductPackagingUnitDataSetInterface::DEFAULT_AMOUNT] > 1 ||
            $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MIN] > 0 ||
            $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MAX] > 0 ||
            $dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_INTERVAL] > 0;

        if (!$haveAmount || $dataSet[ProductPackagingUnitDataSetInterface::IS_LEAD_PRODUCT]) {
            return;
        }

        $productPackagingUnitAmountEntity = $productPackagingUnitEntity->getSpyProductPackagingUnitAmounts()->getFirst();

        if ($productPackagingUnitAmountEntity === null) {
            $productPackagingUnitAmountEntity = new SpyProductPackagingUnitAmount();
            $productPackagingUnitAmountEntity
                ->setFkProductPackagingUnit($productPackagingUnitEntity->getIdProductPackagingUnit());
        }

        $productPackagingUnitAmountEntity
            ->setIsVariable($dataSet[ProductPackagingUnitDataSetInterface::IS_VARIABLE])
            ->setDefaultAmount($dataSet[ProductPackagingUnitDataSetInterface::DEFAULT_AMOUNT])
            ->setAmountMin($dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MIN])
            ->setAmountMax($dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_MAX])
            ->setAmountInterval($dataSet[ProductPackagingUnitDataSetInterface::AMOUNT_INTERVAL])
            ->save();
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getproductPackagingUnitTypeIdByname(string $name): int
    {
        if (isset(static::$productPackagingUnitTypeHeap[$name])) {
            return static::$productPackagingUnitTypeHeap[$name];
        }

        throw new EntityNotFoundException(sprintf("Product Packaging Unit Type '%s' not found", $name));
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getProductConcreteIdByConcreteSku(string $sku): int
    {
        $this->getProductConcreteBySku($sku);

        return static::$productsHeap[$sku][static::CONCRETE_PRODUCT_ID];
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getProductAbstractIdByConcreteSku(string $sku): int
    {
        $this->getProductConcreteBySku($sku);

        return static::$productsHeap[$sku][static::ABSTRACT_PRODUCT_ID];
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function getProductConcreteBySku(string $sku): void
    {
        if (isset(static::$productsHeap[$sku])) {
            return;
        }

        $productEntity = SpyProductQuery::create()
            ->filterBySku($sku)
            ->findOne();

        if ($productEntity === null) {
            throw new EntityNotFoundException(sprintf("Concrete Product with sku '%s' not found", $sku));
        }

        $this->cacheProductConcrete($productEntity);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return void
     */
    protected function cacheProductConcrete(SpyProduct $productEntity): void
    {
        if (static::$productsHeapSize >= static::PRODUCTS_HEAP_LIMIT) {
            static::$productsHeapSize = 0;
            static::$productsHeap = [];
        }

        static::$productsHeapSize++;
        static::$productsHeap[$productEntity->getSku()] = [
            static::CONCRETE_PRODUCT_ID => $productEntity->getIdProduct(),
            static::ABSTRACT_PRODUCT_ID => $productEntity->getFkProductAbstract(),
        ];
    }

    /**
     * @return void
     */
    protected function initProductPackagingUnitTypeHeap()
    {
        $productPackagingUnitTypeEntities = SpyProductPackagingUnitTypeQuery::create()->find();

        foreach ($productPackagingUnitTypeEntities as $packagingUnitTypeEntity) {
            static::$productPackagingUnitTypeHeap[$packagingUnitTypeEntity->getName()] = $packagingUnitTypeEntity->getIdProductPackagingUnitType();
        }

        unset($productPackagingUnitTypeEntities);
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }
}
