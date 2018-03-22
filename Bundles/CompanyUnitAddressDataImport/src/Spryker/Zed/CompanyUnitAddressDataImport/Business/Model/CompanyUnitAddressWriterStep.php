<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model;

use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Company\IdCompanyResolverInterface;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Country\IdCountryResolverInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUnitAddressWriterStep implements DataImportStepInterface
{
    const KEY_ADDRESS_KEY = 'address_key';

    /**
     * @var \Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Company\IdCompanyResolverInterface
     */
    protected $idCompanyResolver;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Country\IdCountryResolverInterface
     */
    protected $idCountryResolver;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Company\IdCompanyResolverInterface $idCompanyResolver
     * @param \Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Country\IdCountryResolverInterface $idCountryResolver
     */
    public function __construct(IdCompanyResolverInterface $idCompanyResolver, IdCountryResolverInterface $idCountryResolver)
    {
        $this->idCompanyResolver = $idCompanyResolver;
        $this->idCountryResolver = $idCountryResolver;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $idCompany = $this->idCompanyResolver->getIdCompany($dataSet);
        $idCountry = $this->idCountryResolver->getIdCountry($dataSet);

        $companyUnitAddressEntity = SpyCompanyUnitAddressQuery::create()
            ->filterByKey($dataSet[static::KEY_ADDRESS_KEY])
            ->findOneOrCreate();

        $companyUnitAddressEntity->fromArray($dataSet->getArrayCopy());
        $companyUnitAddressEntity
            ->setFkCompany($idCompany)
            ->setFkCountry($idCountry);

        $companyUnitAddressEntity->save();
    }
}
