<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step;

use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\DataSet\SspAssetDataSetInterface;

class AssignedBusinessUnitKeysToIdStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $idCompanyBusinessUnitCache = [];

    public function execute(DataSetInterface $dataSet): void
    {
        $assignedBusinessUnitKeys = $dataSet[SspAssetDataSetInterface::ASSIGNED_BUSINESS_UNIT_KEYS] ?? '';

        if (!$assignedBusinessUnitKeys) {
            $dataSet[SspAssetDataSetInterface::ASSIGNED_BUSINESS_UNIT_IDS] = [];

            return;
        }

        $businessUnitKeysList = array_map('trim', explode(',', $assignedBusinessUnitKeys));
        $businessUnitIds = [];

        foreach ($businessUnitKeysList as $businessUnitKey) {
            if (!$businessUnitKey) {
                continue;
            }

            $businessUnitIds[] = $this->getIdCompanyBusinessUnitByKey($businessUnitKey);
        }

        $dataSet[SspAssetDataSetInterface::ASSIGNED_BUSINESS_UNIT_IDS] = $businessUnitIds;
    }

    /**
     * @param string $businessUnitKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return int
     */
    protected function getIdCompanyBusinessUnitByKey(string $businessUnitKey): int
    {
        if (isset($this->idCompanyBusinessUnitCache[$businessUnitKey])) {
            return $this->idCompanyBusinessUnitCache[$businessUnitKey];
        }

        $companyBusinessUnitQuery = SpyCompanyBusinessUnitQuery::create()
            ->select(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT);

        /** @var int|null $idCompanyBusinessUnit */
        $idCompanyBusinessUnit = $companyBusinessUnitQuery
            ->findOneByKey($businessUnitKey);

        if (!$idCompanyBusinessUnit) {
            throw new InvalidDataException(sprintf('Assigned business unit "%s" does not exist', $businessUnitKey));
        }

        $this->idCompanyBusinessUnitCache[$businessUnitKey] = $idCompanyBusinessUnit;

        return $idCompanyBusinessUnit;
    }
}
