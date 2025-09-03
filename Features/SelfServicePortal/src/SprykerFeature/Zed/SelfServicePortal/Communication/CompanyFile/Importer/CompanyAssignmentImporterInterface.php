<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer;

use Generated\Shared\Transfer\CompanyAssignmentFileParserResponseTransfer;

interface CompanyAssignmentImporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyAssignmentFileParserResponseTransfer $companyAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToAssign(CompanyAssignmentFileParserResponseTransfer $companyAssignmentFileParserResponseTransfer, int $idFile): array;

    /**
     * @param \Generated\Shared\Transfer\CompanyAssignmentFileParserResponseTransfer $companyAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToUnassign(CompanyAssignmentFileParserResponseTransfer $companyAssignmentFileParserResponseTransfer, int $idFile): array;
}
