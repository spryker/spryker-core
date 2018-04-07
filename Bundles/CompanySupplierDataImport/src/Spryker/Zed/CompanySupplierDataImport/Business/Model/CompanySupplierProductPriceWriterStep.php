<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierDataImport\Business\Model;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\DataSet\CompanySupplierDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;

class CompanySupplierProductPriceWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const PRICE_TYPE_SUPPLIER = 'SUPPLIER';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceTypeEntity = SpyPriceTypeQuery::create()
            ->filterByName(static::PRICE_TYPE_SUPPLIER)
            ->findOneOrCreate();

        if ($priceTypeEntity->isNew() || $priceTypeEntity->isModified()) {
            $priceTypeEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
            $priceTypeEntity->save();
        }

        $query = SpyPriceProductQuery::create();
        $query->filterByFkPriceType($priceTypeEntity->getIdPriceType());

        $this->addPublishEvents(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $dataSet[CompanySupplierDataSet::PRODUCT_ID]);
        $query->filterByFkProduct($dataSet[CompanySupplierDataSet::PRODUCT_ID]);
        $query->filterByFkCompany($dataSet[CompanySupplierDataSet::COMPANY_ID]);

        $productPriceEntity = $query->findOneOrCreate();
        $productPriceEntity->save();

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($dataSet[CompanySupplierDataSet::STORE_ID])
            ->filterByFkCurrency($dataSet[CompanySupplierDataSet::CURRENCY_ID])
            ->filterByFkPriceProduct($productPriceEntity->getPrimaryKey())
            ->findOneOrCreate();

        $priceProductStoreEntity->setGrossPrice($dataSet[CompanySupplierDataSet::PRICE_GROSS]);
        $priceProductStoreEntity->setNetPrice($dataSet[CompanySupplierDataSet::PRICE_NET]);

        $priceProductStoreEntity->save();
    }
}
