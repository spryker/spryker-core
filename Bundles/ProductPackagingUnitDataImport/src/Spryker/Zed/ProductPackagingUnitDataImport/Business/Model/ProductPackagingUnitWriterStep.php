<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductPackagingLeadProductQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitAmount;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
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
        $productPackagingUnitEntity = SpyProductPackagingUnitQuery::create()
            ->useProductQuery()
                ->filterBySku($dataSet[ProductPackagingUnitDataSet::CONCRETE_SKU])
            ->endUse()
            ->useProductPackagingUnitTypeQuery()
                ->filterByName($dataSet[ProductPackagingUnitDataSet::TYPE_NAME])
            ->endUse()
            ->findOneOrCreate();

        $productPackagingUnitEntity
            ->setHasLeadProduct(boolval($dataSet[ProductPackagingUnitDataSet::HAS_LEAD_PRODUCT]));

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
        if (boolval($dataSet[ProductPackagingUnitDataSet::IS_LEAD_PRODUCT])) {
            $productPackagingLeadProdductEntity = SpyProductPackagingLeadProductQuery::create()
                ->filterByFkProductAbstract($this->getProductAbstractIdByConcreteSku($dataSet[ProductPackagingUnitDataSet::CONCRETE_SKU]))
                ->findOneOrCreate();

            $productPackagingLeadProdductEntity
                ->setFkProduct($productPackagingUnitEntity->getFkProduct())
                ->save();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit $productPackagingUnitEntity
     *
     * @return void
     */
    protected function persistAmount(DataSetInterface $dataSet, SpyProductPackagingUnit $productPackagingUnitEntity): void
    {
        if (
            (int)$dataSet[ProductPackagingUnitDataSet::DEFAULT_AMOUNT] > 1 &&
            (int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_MIN] > 1 &&
            (int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_MAX] > 1 &&
            (int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_INTERVAL] > 1
        ) {
            $productPackagingUnitAmountEntity = $productPackagingUnitEntity->getSpyProductPackagingUnitAmounts()->getFirst();

            if ($productPackagingUnitAmountEntity === null()) {
                $productPackagingUnitAmountEntity = new SpyProductPackagingUnitAmount();
                $productPackagingUnitAmountEntity
                    ->setFkProductPackagingUnit($productPackagingUnitEntity->getIdProductPackagingUnit());
            }

            $productPackagingUnitAmountEntity
                ->setIsVariable(boolval($dataSet[ProductPackagingUnitDataSet::IS_VARIABLE]))
                ->setDefaultAmount((int)$dataSet[ProductPackagingUnitDataSet::DEFAULT_AMOUNT])
                ->setAmountMin((int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_MIN])
                ->setAmountMax((int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_MAX])
                ->setAmountInterval((int)$dataSet[ProductPackagingUnitDataSet::AMOUNT_INTERVAL])
                ->save();
        }
    }

    /**
     * @param string $name
     *
     * @return int
     */
    protected function getproductPackagingUnitTypeIdByname(string $name)
    {
        $productPackagingUnitTypeEntity = SpyProductPackagingUnitTypeQuery::create()
            ->filterByName($dataSet[ProductPackagingUnitDataSet::TYPE_NAME])
            ->findOneOrCreate();
        
        if ($productPackagingUnitTypeEntity->isNew()) {
            $productPackagingUnitTypeEntity
                ->setName($dataSet[ProductPackagingUnitDataSet::TYPE_NAME])
                ->save();
        }

        return $productPackagingUnitTypeEntity->getIdProductPackagingUnitType();
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getProductIdByConcreteSku(string $sku)
    {
        return SpyProductQuery::create()
            ->filterBySku($sku)
            ->findOne()
            ->getIdProduct();
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getProductAbstractIdByConcreteSku(string $sku)
    {
        return SpyProductQuery::create()
            ->filterBySku($sku)
            ->findOne()
            ->getFkProductAbstract();
    }
}
