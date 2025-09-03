<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer;

use Generated\Shared\Transfer\CompanyUserAssignmentFileParserResponseTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class CompanyUserAssignmentImporter implements CompanyUserAssignmentImporterInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $repository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserAssignmentFileParserResponseTransfer $companyUserAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return list<int>
     */
    public function getIdsToAssign(CompanyUserAssignmentFileParserResponseTransfer $companyUserAssignmentFileParserResponseTransfer, int $idFile): array
    {
        $companyUserIds = $companyUserAssignmentFileParserResponseTransfer->getReferencesToAssign();
        if (!$companyUserIds) {
            return [];
        }

        return $this->repository->getCompanyUserIdsToAssign($companyUserIds, $idFile);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserAssignmentFileParserResponseTransfer $companyUserAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return list<int>
     */
    public function getIdsToUnassign(CompanyUserAssignmentFileParserResponseTransfer $companyUserAssignmentFileParserResponseTransfer, int $idFile): array
    {
        $companyUserIds = $companyUserAssignmentFileParserResponseTransfer->getReferencesToDeassign();
        if (!$companyUserIds) {
            return [];
        }

        return $this->repository->getCompanyUserIdsToUnassign($companyUserIds, $idFile);
    }
}
