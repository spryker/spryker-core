<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business;

use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\CompanyUnitAddressWriterStep;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Company\IdCompanyResolver;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Company\IdCompanyResolverInterface;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Country\IdCountryResolver;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Country\IdCountryResolverInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddressDataImport\CompanyUnitAddressDataImportConfig getConfig()
 */
class CompanyUnitAddressDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyUnitAddressDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyUnitAddressDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CompanyUnitAddressWriterStep(
            $this->createIdCompanyResolver(),
            $this->createIdCountryResolver()
        ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Company\IdCompanyResolverInterface
     */
    public function createIdCompanyResolver(): IdCompanyResolverInterface
    {
        return new IdCompanyResolver();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Country\IdCountryResolverInterface
     */
    public function createIdCountryResolver(): IdCountryResolverInterface
    {
        return new IdCountryResolver();
    }
}
