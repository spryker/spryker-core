<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer;

use Generated\Shared\Transfer\SspAssetAssignmentFileParserResponseTransfer;

interface AssetAssignmentImporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspAssetAssignmentFileParserResponseTransfer $sspAssetAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToAssign(SspAssetAssignmentFileParserResponseTransfer $sspAssetAssignmentFileParserResponseTransfer, int $idFile): array;

    /**
     * @param \Generated\Shared\Transfer\SspAssetAssignmentFileParserResponseTransfer $sspAssetAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return array<int>
     */
    public function getIdsToUnassign(SspAssetAssignmentFileParserResponseTransfer $sspAssetAssignmentFileParserResponseTransfer, int $idFile): array;
}
