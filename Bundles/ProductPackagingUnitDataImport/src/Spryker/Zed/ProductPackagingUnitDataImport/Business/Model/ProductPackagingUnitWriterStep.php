<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductPackagingLeadProductQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitAmount;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductPackagingUnitDataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\DataSet\ProductPackagingUnitDataSet;

class ProductPackagingUnitWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productPackagingUnitEntity = $this->getProductPackagingUnitQuery()
            ->useProductQuery()
                ->filterBySku($dataSet[ProductPackagingUnitDataSet::CONCRETE_SKU])
            ->endUse()
            ->useProductPackagingUnitTypeQuery(null, Criteria::LEFT_JOIN)
                ->filterByName($dataSet[ProductPackagingUnitDataSet::TYPE_NAME])
            ->endUse()
            ->findOne();

        if ($productPackagingUnitEntity === null) {
            $productPackagingUnitEntity = new SpyProductPackagingUnit();
        }

        $productPackagingUnitEntity
            ->setHasLeadProduct((bool)$dataSet[ProductPackagingUnitDataSet::HAS_LEAD_PRODUCT]);

        if ($productPackagingUnitEntity->isNew()) {
            $productPackagingUnitEntity
                ->setFkProduct($this->getProductIdByConcreteSku($dataSet[ProductPackagingUnitDataSet::CONCRETE_SKU]))
                ->setFkProductPackagingUnitType($this->getproductPackagingUnitTypeIdByname($dataSet[ProductPackagingUnitDataSet::TYPE_NAME]));
        }

        $productPackagingUnitEntity->save();

        $this->persistLeadProduct($dataSet, $productPackagingUnitEntity);
        $this->persistAmount($dataSet, $productPackagingUnitEntity);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit $productPackagingUnitEntity
     *
     * @return void
     */
    protected function persistLeadProduct(DataSetInterface $dataSet, SpyProductPackagingUnit $productPackagingUnitEntity): void
    {
        $shouldpersistLeadProduct = (bool)$dataSet[ProductPackagingUnitDataSet::IS_LEAD_PRODUCT];

        if (!$shouldpersistLeadProduct) {
            return;
        }

        $productPackagingLeadProductEntity = SpyProductPackagingLeadProductQuery::create()
            ->filterByFkProductAbstract($this->getProductAbstractIdByConcreteSku($dataSet[ProductPackagingUnitDataSet::CONCRETE_SKU]))
            ->findOneOrCreate();

        $productPackagingLeadProductEntity
            ->setFkProduct($productPackagingUnitEntity->getFkProduct())
            ->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit $productPackagingUnitEntity
     *
     * @return void
     */
    protected function persistAmount(DataSetInterface $dataSet, SpyProductPackagingUnit $productPackagingUnitEntity): void
    {
        $shouldpersistAmount = (int)$dataSet[ProductPackagingUnitDataSet::DEFAULT_AMOUNT] > 1 &&
            (int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_MIN] > 1 &&
            (int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_MAX] > 1 &&
            (int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_INTERVAL] > 1;

        if (!$shouldpersistAmount) {
            return;
        }

        $productPackagingUnitAmountEntity = $productPackagingUnitEntity->getSpyProductPackagingUnitAmounts()->getFirst();

        if ($productPackagingUnitAmountEntity === null) {
            $productPackagingUnitAmountEntity = new SpyProductPackagingUnitAmount();
            $productPackagingUnitAmountEntity
                ->setFkProductPackagingUnit($productPackagingUnitEntity->getIdProductPackagingUnit());
        }

        $productPackagingUnitAmountEntity
            ->setIsVariable((bool)$dataSet[ProductPackagingUnitDataSet::IS_VARIABLE])
            ->setDefaultAmount((int)$dataSet[ProductPackagingUnitDataSet::DEFAULT_AMOUNT])
            ->setAmountMin((int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_MIN])
            ->setAmountMax((int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_MAX])
            ->setAmountInterval((int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_INTERVAL])
            ->save();
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\ProductPackagingUnitDataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getproductPackagingUnitTypeIdByname(string $name): int
    {
        $productPackagingUnitTypeEntity = SpyProductPackagingUnitTypeQuery::create()
            ->filterByName($name)
            ->findOneOrCreate();

        if ($productPackagingUnitTypeEntity->isNew()) {
            throw new EntityNotFoundException(sprintf("Product Packaging Unit Type '%s' not found", $name));
        }

        return $productPackagingUnitTypeEntity->getIdProductPackagingUnitType();
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\ProductPackagingUnitDataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getProductIdByConcreteSku(string $sku): int
    {

        $productEntity = SpyProductQuery::create()
            ->filterBySku($sku)
            ->findOne();

        if ($productEntity === null) {
            throw new EntityNotFoundException(sprintf("Concrete Product with sku '%s' not found", $sku));
        }

        return $productEntity->getIdProduct();
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getProductAbstractIdByConcreteSku(string $sku): int
    {
        return SpyProductQuery::create()
            ->filterBySku($sku)
            ->findOne()
            ->getFkProductAbstract();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }
}
