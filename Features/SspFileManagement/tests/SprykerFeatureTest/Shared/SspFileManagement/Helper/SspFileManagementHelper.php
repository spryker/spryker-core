<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Shared\SspFileManagement\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\FileAttachmentBuilder;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use InvalidArgumentException;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class SspFileManagementHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seedData
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function haveFileAttachment(array $seedData): FileAttachmentTransfer
    {
        $fileAttachmentTransfer = (new FileAttachmentBuilder($seedData))->build();

        $entityName = $fileAttachmentTransfer->getEntityNameOrFail();
        $entityId = $fileAttachmentTransfer->getEntityIdOrFail();
        $idFile = $fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail();

        match ($entityName) {
            SspFileManagementConfig::ENTITY_TYPE_COMPANY => $this->createCompanyFileAttachment($idFile, $entityId),
            SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER => $this->createCompanyUserFileAttachment($idFile, $entityId),
            SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => $this->createCompanyBusinessUnitFileAttachment($idFile, $entityId),
            default => throw new InvalidArgumentException("Invalid entity type: $entityName"),
        };

        return $fileAttachmentTransfer;
    }

    /**
     * @return void
     */
    public function ensureFileAttachmentTablesAreEmpty(): void
    {
        $this->createCompanyFileQuery()->deleteAll();
        $this->createCompanyUserFileQuery()->deleteAll();
        $this->createCompanyBusinessUnitFileQuery()->deleteAll();
    }

    /**
     * @param int $idFile
     * @param int $idCompany
     *
     * @return void
     */
    protected function createCompanyFileAttachment(int $idFile, int $idCompany): void
    {
        $companyFileEntity = $this->createCompanyFileQuery()
            ->filterByFkFile($idFile)
            ->filterByFkCompany($idCompany)
            ->findOneOrCreate();

        $companyFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($companyFileEntity): void {
            $companyFileEntity->delete();
        });
    }

    /**
     * @param int $idFile
     * @param int $idCompanyUser
     *
     * @return void
     */
    protected function createCompanyUserFileAttachment(int $idFile, int $idCompanyUser): void
    {
        $companyUserFileEntity = $this->createCompanyUserFileQuery()
            ->filterByFkFile($idFile)
            ->filterByFkCompanyUser($idCompanyUser)
            ->findOneOrCreate();

        $companyUserFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($companyUserFileEntity): void {
            $companyUserFileEntity->delete();
        });
    }

    /**
     * @param int $idFile
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    protected function createCompanyBusinessUnitFileAttachment(
        int $idFile,
        int $idCompanyBusinessUnit
    ): void {
        $companyBusinessUnitFileEntity = $this->createCompanyBusinessUnitFileQuery()
            ->filterByFkFile($idFile)
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->findOneOrCreate();

        $companyBusinessUnitFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($companyBusinessUnitFileEntity): void {
            $companyBusinessUnitFileEntity->delete();
        });
    }

    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery
     */
    public function createCompanyFileQuery(): SpyCompanyFileQuery
    {
        return SpyCompanyFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery
     */
    public function createCompanyUserFileQuery(): SpyCompanyUserFileQuery
    {
        return SpyCompanyUserFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery
     */
    public function createCompanyBusinessUnitFileQuery(): SpyCompanyBusinessUnitFileQuery
    {
        return SpyCompanyBusinessUnitFileQuery::create();
    }
}
