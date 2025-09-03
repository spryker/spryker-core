<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer;

use Generated\Shared\Transfer\BusinessUnitAssignmentFileParserResponseTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class BusinessUnitAssignmentImporter implements BusinessUnitAssignmentImporterInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $repository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\BusinessUnitAssignmentFileParserResponseTransfer $responseTransfer
     * @param int $idFile
     *
     * @return list<int>
     */
    public function getIdsToAssign(BusinessUnitAssignmentFileParserResponseTransfer $responseTransfer, int $idFile): array
    {
        $businessUnitIds = $responseTransfer->getReferencesToAssign();
        if (!$businessUnitIds) {
            return [];
        }

        return $this->repository->getBusinessUnitIdsToAssign(array_map('intval', $businessUnitIds), $idFile);
    }

    /**
     * @param \Generated\Shared\Transfer\BusinessUnitAssignmentFileParserResponseTransfer $responseTransfer
     * @param int $idFile
     *
     * @return list<int>
     */
    public function getIdsToUnassign(BusinessUnitAssignmentFileParserResponseTransfer $responseTransfer, int $idFile): array
    {
        $businessUnitIds = $responseTransfer->getReferencesToDeassign();
        if (!$businessUnitIds) {
            return [];
        }

        return $this->repository->getBusinessUnitIdsToUnassign(array_map('intval', $businessUnitIds), $idFile);
    }
}
