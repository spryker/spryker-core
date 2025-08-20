<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Storage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitStorageTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentStorageTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetStorageTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;

class SspAssetStorageMapper implements SspAssetStorageMapperInterface
{
    /**
     * @var string
     */
    protected const KEY_BUSINESS_UNIT_IDS = 'business_unit_ids';

    /**
     * @var string
     */
    protected const KEY_COMPANY_IDS = 'company_ids';

    /**
     * @var string
     */
    protected const KEY_ID_OWNER_BUSINESS_UNIT = 'id_owner_business_unit';

    /**
     * @var string
     */
    protected const KEY_ID_OWNER_COMPANY_ID = 'id_owner_company_id';

    /**
     * @param array<string, mixed> $storageData
     *
     * @return \Generated\Shared\Transfer\SspAssetStorageTransfer
     */
    public function mapStorageDataToSspAssetStorageTransfer(array $storageData): SspAssetStorageTransfer
    {
        $sspAssetStorageTransfer = new SspAssetStorageTransfer();
        $sspAssetStorageTransfer->fromArray($storageData, true);

        $sspAssetStorageTransfer = $this->mapBusinessUnitAssignmentsToAssetStorageTransfer($sspAssetStorageTransfer, $storageData);
        $sspAssetStorageTransfer = $this->mapCompanyBusinessUnitToAssetStorageTransfer($sspAssetStorageTransfer, $storageData);

        return $sspAssetStorageTransfer;
    }

    /**
     * @param array<string, mixed> $storageData
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function mapStorageDataToSspAssetTransferWithCompanyAssignmentsOnly(array $storageData, SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $sspAssetTransfer = new SspAssetTransfer();
        $sspAssetTransfer->fromArray($storageData, true);

        $sspAssetTransfer = $this->mapCompanyBusinessUnitToAssetTransfer($sspAssetTransfer, $storageData);

        if (!isset($storageData[static::KEY_COMPANY_IDS]) || !is_array($storageData[static::KEY_COMPANY_IDS])) {
            return $sspAssetTransfer;
        }

        $businessUnitAssignmentTransfers = [];
        foreach ($storageData[static::KEY_COMPANY_IDS] as $companyId) {
            $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
                ->setFkCompany($companyId);

            $businessUnitAssignmentTransfer = (new SspAssetBusinessUnitAssignmentTransfer())
                ->setCompanyBusinessUnit($companyBusinessUnitTransfer);

            $businessUnitAssignmentTransfers[] = $businessUnitAssignmentTransfer;
        }

        $sspAssetTransfer->setBusinessUnitAssignments(new ArrayObject($businessUnitAssignmentTransfers));

        return $sspAssetTransfer;
    }

    /**
     * @param array<string, mixed> $storageData
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function mapStorageDataToSspAssetTransferWithBusinessUnitAssignmentsOnly(array $storageData, SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $sspAssetTransfer = new SspAssetTransfer();
        $sspAssetTransfer->fromArray($storageData, true);

        $sspAssetTransfer = $this->mapCompanyBusinessUnitToAssetTransfer($sspAssetTransfer, $storageData);

        if (!isset($storageData[static::KEY_BUSINESS_UNIT_IDS]) || !is_array($storageData[static::KEY_BUSINESS_UNIT_IDS])) {
            return $sspAssetTransfer;
        }

        $businessUnitAssignmentTransfers = [];
        foreach ($storageData[static::KEY_BUSINESS_UNIT_IDS] as $idCompanyBusinessUnit) {
            $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
                ->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

            $businessUnitAssignmentTransfer = (new SspAssetBusinessUnitAssignmentTransfer())
                ->setCompanyBusinessUnit($companyBusinessUnitTransfer);

            $businessUnitAssignmentTransfers[] = $businessUnitAssignmentTransfer;
        }

        $sspAssetTransfer->setBusinessUnitAssignments(new ArrayObject($businessUnitAssignmentTransfers));

        return $sspAssetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetStorageTransfer $sspAssetStorageTransfer
     * @param array<string, mixed> $storageData
     *
     * @return \Generated\Shared\Transfer\SspAssetStorageTransfer
     */
    protected function mapBusinessUnitAssignmentsToAssetStorageTransfer(
        SspAssetStorageTransfer $sspAssetStorageTransfer,
        array $storageData
    ): SspAssetStorageTransfer {
        if (!isset($storageData[static::KEY_BUSINESS_UNIT_IDS]) || !is_array($storageData[static::KEY_BUSINESS_UNIT_IDS])) {
            return $sspAssetStorageTransfer;
        }

        $businessUnitAssignments = [];
        foreach ($storageData[static::KEY_BUSINESS_UNIT_IDS] as $businessUnitId) {
            $businessUnitAssignments[] = (new SspAssetBusinessUnitAssignmentStorageTransfer())
                ->setCompanyBusinessUnit(
                    (new CompanyBusinessUnitStorageTransfer())
                        ->setIdCompanyBusinessUnit($businessUnitId),
                );
        }

        $sspAssetStorageTransfer->setBusinessUnitAssignments(new ArrayObject($businessUnitAssignments));

        return $sspAssetStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetStorageTransfer $sspAssetStorageTransfer
     * @param array<string, mixed> $storageData
     *
     * @return \Generated\Shared\Transfer\SspAssetStorageTransfer
     */
    protected function mapCompanyBusinessUnitToAssetStorageTransfer(
        SspAssetStorageTransfer $sspAssetStorageTransfer,
        array $storageData
    ): SspAssetStorageTransfer {
        if (!isset($storageData[static::KEY_ID_OWNER_BUSINESS_UNIT]) || !isset($storageData[static::KEY_ID_OWNER_COMPANY_ID])) {
            return $sspAssetStorageTransfer;
        }

        $companyBusinessUnit = (new CompanyBusinessUnitStorageTransfer())
            ->setIdCompanyBusinessUnit($storageData[static::KEY_ID_OWNER_BUSINESS_UNIT]);

        $sspAssetStorageTransfer->setCompanyBusinessUnit($companyBusinessUnit);

        return $sspAssetStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param array<string, mixed> $storageData
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    protected function mapCompanyBusinessUnitToAssetTransfer(
        SspAssetTransfer $sspAssetTransfer,
        array $storageData
    ): SspAssetTransfer {
        if (!isset($storageData[static::KEY_ID_OWNER_BUSINESS_UNIT]) || !isset($storageData[static::KEY_ID_OWNER_COMPANY_ID])) {
            return $sspAssetTransfer;
        }

        $companyBusinessUnit = (new CompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit($storageData[static::KEY_ID_OWNER_BUSINESS_UNIT]);

        $sspAssetTransfer->setCompanyBusinessUnit($companyBusinessUnit);

        return $sspAssetTransfer;
    }
}
