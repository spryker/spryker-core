<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierDataImport\Business;

use Spryker\Zed\CompanySupplierDataImport\Business\Model\CompanyTypeWriterStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

/**
 * @method \Spryker\Zed\CompanySupplierDataImport\CompanySupplierDataImportConfig getConfig()
 */
class CompanySupplierDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyTypeDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyTypeDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CompanyTypeWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }
}
