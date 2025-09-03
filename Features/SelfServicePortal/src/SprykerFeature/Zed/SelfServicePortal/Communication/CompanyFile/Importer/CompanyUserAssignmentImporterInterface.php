<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer;

use Generated\Shared\Transfer\CompanyUserAssignmentFileParserResponseTransfer;

interface CompanyUserAssignmentImporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserAssignmentFileParserResponseTransfer $companyUserAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToAssign(CompanyUserAssignmentFileParserResponseTransfer $companyUserAssignmentFileParserResponseTransfer, int $idFile): array;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserAssignmentFileParserResponseTransfer $companyUserAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToUnassign(CompanyUserAssignmentFileParserResponseTransfer $companyUserAssignmentFileParserResponseTransfer, int $idFile): array;
}
