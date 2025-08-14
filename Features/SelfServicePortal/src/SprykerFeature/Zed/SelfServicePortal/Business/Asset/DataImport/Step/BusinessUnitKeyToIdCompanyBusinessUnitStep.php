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

class BusinessUnitKeyToIdCompanyBusinessUnitStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $idCompanyBusinessUnitCache = [];

    public function execute(DataSetInterface $dataSet): void
    {
        $businessUnitKey = $dataSet[SspAssetDataSetInterface::BUSINESS_UNIT_KEY] ?? '';

        if (!$businessUnitKey) {
            return;
        }

        $businessUnitId = $this->getIdCompanyBusinessUnitByKey($businessUnitKey);
        $dataSet[SspAssetDataSetInterface::FK_COMPANY_BUSINESS_UNIT] = $businessUnitId;
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
            throw new InvalidDataException(sprintf('"%s" does not exist', $businessUnitKey));
        }

        $this->idCompanyBusinessUnitCache[$businessUnitKey] = $idCompanyBusinessUnit;

        return $idCompanyBusinessUnit;
    }
}
