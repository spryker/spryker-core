<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipDataImport\Business\Model\Step;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantRelationshipDataImport\Business\Model\DataSet\MerchantRelationshipDataSetInterface;

class CompanyBusinessUnitOwnerKeyToIdCompanyBusinessUnitStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCompanyBusinessUnitCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyBusinessUnitKey = $dataSet[MerchantRelationshipDataSetInterface::COMPANY_BUSINESS_UNIT_OWNER_KEY];
        if (!$companyBusinessUnitKey) {
            throw new InvalidDataException('"' . MerchantRelationshipDataSetInterface::COMPANY_BUSINESS_UNIT_OWNER_KEY . '" is required.');
        }

        if (!isset($this->idCompanyBusinessUnitCache[$companyBusinessUnitKey])) {
            $idCompanyBusinessUnit = SpyCompanyBusinessUnitQuery::create()
                ->select(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT)
                ->findOneByKey($companyBusinessUnitKey);

            if (!$idCompanyBusinessUnit) {
                throw new EntityNotFoundException(sprintf('Could not find Company Business Unit by key "%s"', $companyBusinessUnitKey));
            }

            $this->idCompanyBusinessUnitCache[$companyBusinessUnitKey] = $idCompanyBusinessUnit;
        }

        $dataSet[MerchantRelationshipDataSetInterface::ID_COMPANY_BUSINESS_UNIT] = $this->idCompanyBusinessUnitCache[$companyBusinessUnitKey];
    }
}
