<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Resolver\Company;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\CompanyUnitAddressDataImport\Exception\CompanyNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class IdCompanyResolver implements IdCompanyResolverInterface
{
    const KEY_COMPANY_KEY = 'company_key';

    /**
     * @var array
     */
    protected $idCompanyCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\CompanyUnitAddressDataImport\Exception\CompanyNotFoundException
     *
     * @return int
     */
    public function getIdCompany(DataSetInterface $dataSet): int
    {
        $companyKey = $dataSet[static::KEY_COMPANY_KEY];

        if (isset($this->idCompanyCache[$companyKey])) {
            return $this->idCompanyCache[$companyKey];
        }

        $companyEntity = SpyCompanyQuery::create()
            ->findOneByKey($companyKey);

        if (!$companyEntity) {
            throw new CompanyNotFoundException(sprintf('Company with key "%s" not found!', $companyKey));
        }

        $this->idCompanyCache[$companyKey] = $companyEntity->getIdCompany();

        return $companyEntity->getIdCompany();
    }
}
