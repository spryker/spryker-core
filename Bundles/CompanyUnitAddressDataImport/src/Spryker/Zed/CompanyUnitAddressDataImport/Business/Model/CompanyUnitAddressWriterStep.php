<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUnitAddressWriterStep implements DataImportStepInterface
{
    const KEY_FK_COUNTRY = 'fk_country';
    const KEY_FK_COMPANY = 'fk_company';
    const KEY_ADDRESS1 = 'address1';
    const KEY_ADDRESS2 = 'address2';
    const KEY_ADDRESS3 = 'address3';
    const KEY_CITY = 'city';
    const KEY_ZIP_CODE = 'zip_code';
    const KEY_PHONE = 'phone';
    const KEY_COMMENT = 'comment';

    /**
     * TODO: the current implementation disallow updates for CompanyUnitAddresses.
     *
     * We should add an identifier to the database table of this entity to clearly identify the entity for update or insert.
     *
     * E.g. test-company-address-1
     * - if the key exists -> update company unit address
     * - if the key not exists -> fetch id of company and create new address
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $idCountry = SpyCountryQuery::create()
            ->findOne()
            ->getIdCountry();

        $idCompany = SpyCompanyQuery::create()
            ->findOne()
            ->getIdCompany();

        $companyUnitAddressEntity = SpyCompanyUnitAddressQuery::create()
            ->filterByFkCountry($idCountry)
            ->filterByFkCompany($idCompany)
            ->filterByAddress1($dataSet[static::KEY_ADDRESS1])
            ->filterByAddress2($dataSet[static::KEY_ADDRESS2])
            ->filterByAddress3($dataSet[static::KEY_ADDRESS3])
            ->filterByCity($dataSet[static::KEY_CITY])
            ->filterByZipCode($dataSet[static::KEY_ZIP_CODE])
            ->filterByPhone($dataSet[static::KEY_PHONE])
            ->filterByComment($dataSet[static::KEY_COMMENT])
            ->findOneOrCreate();

        $companyUnitAddressEntity->save();
    }
}
