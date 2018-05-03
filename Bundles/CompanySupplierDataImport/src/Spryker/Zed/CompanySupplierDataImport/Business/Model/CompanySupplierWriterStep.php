<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierDataImport\Business\Model;

use Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProductQuery;
use Spryker\Zed\CompanySupplierDataImport\Business\Model\DataSet\CompanySupplierDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanySupplierWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companySupplierToProductEntity = SpyCompanySupplierToProductQuery::create()
            ->filterByFkCompany($dataSet[CompanySupplierDataSet::COMPANY_ID])
            ->filterByFkProduct($dataSet[CompanySupplierDataSet::PRODUCT_ID])
            ->findOneOrCreate();
        $companySupplierToProductEntity->save();
    }
}
