<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer;

use Generated\Shared\Transfer\CompanyAssignmentFileParserResponseTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class CompanyAssignmentImporter implements CompanyAssignmentImporterInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $repository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyAssignmentFileParserResponseTransfer $companyAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToAssign(CompanyAssignmentFileParserResponseTransfer $companyAssignmentFileParserResponseTransfer, int $idFile): array
    {
        $companyIds = $companyAssignmentFileParserResponseTransfer->getReferencesToAssign();
        if (!$companyIds) {
            return [];
        }

        return $this->repository->getExistingCompanyIds($companyIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyAssignmentFileParserResponseTransfer $companyAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToUnassign(CompanyAssignmentFileParserResponseTransfer $companyAssignmentFileParserResponseTransfer, int $idFile): array
    {
        $companyIds = $companyAssignmentFileParserResponseTransfer->getReferencesToDeassign();
        if (!$companyIds) {
            return [];
        }

        return $this->repository->getExistingCompanyIds($companyIds);
    }
}
