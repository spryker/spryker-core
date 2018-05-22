<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipDataImport\Business\Model\Step;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantRelationshipDataImport\Business\Model\DataSet\MerchantRelationshipDataSet;

class CompanyBusinessUnitAssigneeKeysToIdCompanyBusinessUnitCollectionStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected $assigneeDelimiter = ';';

    /**
     * @var array
     */
    protected $idCompanyBusinessUnitCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        if (!$dataSet[MerchantRelationshipDataSet::COMPANY_BUSINESS_UNIT_ASSIGNEE_KEYS]) {
            $dataSet[MerchantRelationshipDataSet::ID_COMPANY_BUSINESS_UNIT_ASSIGNEE_COLLECTION] = [];

            return;
        }

        $companyBusinessUnitKeys = explode(
            $this->getAssigneeDelimiter(),
            $dataSet[MerchantRelationshipDataSet::COMPANY_BUSINESS_UNIT_ASSIGNEE_KEYS]
        );

        $companyBusinessUnitAssignee = [];
        foreach ($companyBusinessUnitKeys as $companyBusinessUnitKey) {
            if (!isset($this->idCompanyBusinessUnitCache[$companyBusinessUnitKey])) {
                $idCompanyBusinessUnit = SpyCompanyBusinessUnitQuery::create()
                    ->select(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT)
                    ->findOneByKey($companyBusinessUnitKey);

                if (!$idCompanyBusinessUnit) {
                    throw new EntityNotFoundException(sprintf('Could not find Company Business Unit by key "%s"', $companyBusinessUnitKey));
                }

                $this->idCompanyBusinessUnitCache[$companyBusinessUnitKey] = $idCompanyBusinessUnit;
            }

            $companyBusinessUnitAssignee[] = $this->idCompanyBusinessUnitCache[$companyBusinessUnitKey];
        }

        $dataSet[MerchantRelationshipDataSet::ID_COMPANY_BUSINESS_UNIT_ASSIGNEE_COLLECTION] = $companyBusinessUnitAssignee;
    }

    /**
     * @return string
     */
    public function getAssigneeDelimiter(): string
    {
        return $this->assigneeDelimiter;
    }

    /**
     * @param string $assigneeDelimiter
     *
     * @return void
     */
    public function setAssigneeDelimiter(string $assigneeDelimiter): void
    {
        $this->assigneeDelimiter = $assigneeDelimiter;
    }
}
