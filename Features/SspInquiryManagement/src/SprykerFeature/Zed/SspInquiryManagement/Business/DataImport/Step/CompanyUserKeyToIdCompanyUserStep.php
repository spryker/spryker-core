<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\Step;

use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\DataSet\SspInquiryDataSetInterface;

class CompanyUserKeyToIdCompanyUserStep implements DataImportStepInterface
{
    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $companyUserQuery
     */
    public function __construct(protected SpyCompanyUserQuery $companyUserQuery)
    {
    }

    /**
     * @var array<int>
     */
    protected $idCompanyUserCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $companyUserKey = $dataSet[SspInquiryDataSetInterface::KEY_COMPANY_USER];
        if (!isset($this->idCompanyUserCache[$companyUserKey])) {
            /** @var int|null $idCompanyUser */
            $idCompanyUser = $this->companyUserQuery
                ->clear()
                ->select(SpyCompanyUserTableMap::COL_ID_COMPANY_USER)
                ->findOneByKey($companyUserKey);

            if (!$idCompanyUser) {
                throw new EntityNotFoundException(sprintf('Could not find company user by key "%s"', $companyUserKey));
            }

            $this->idCompanyUserCache[$companyUserKey] = $idCompanyUser;
        }

        $dataSet[SspInquiryDataSetInterface::FK_COMPANY_USER] = $this->idCompanyUserCache[$companyUserKey];
    }
}
