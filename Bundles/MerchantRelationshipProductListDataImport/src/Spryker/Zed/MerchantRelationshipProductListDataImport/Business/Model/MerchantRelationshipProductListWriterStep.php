<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model;

use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\DataSet\MerchantRelationshipProductListDataSetInterface;

class MerchantRelationshipProductListWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        SpyProductListQuery::create()
            ->findOneByIdProductList($dataSet[MerchantRelationshipProductListDataSetInterface::ID_PRODUCT_LIST])
            ->setFkMerchantRelationship($dataSet[MerchantRelationshipProductListDataSetInterface::ID_MERCHANT_RELATIONSHIP])
            ->save();
    }
}
