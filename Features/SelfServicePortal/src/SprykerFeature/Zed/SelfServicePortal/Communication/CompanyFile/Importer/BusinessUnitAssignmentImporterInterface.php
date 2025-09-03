<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer;

use Generated\Shared\Transfer\BusinessUnitAssignmentFileParserResponseTransfer;

interface BusinessUnitAssignmentImporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\BusinessUnitAssignmentFileParserResponseTransfer $responseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToAssign(BusinessUnitAssignmentFileParserResponseTransfer $responseTransfer, int $idFile): array;

    /**
     * @param \Generated\Shared\Transfer\BusinessUnitAssignmentFileParserResponseTransfer $responseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToUnassign(BusinessUnitAssignmentFileParserResponseTransfer $responseTransfer, int $idFile): array;
}
