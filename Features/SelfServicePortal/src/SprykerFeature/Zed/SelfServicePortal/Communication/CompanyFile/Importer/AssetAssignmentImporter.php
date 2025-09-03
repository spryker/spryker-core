<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer;

use Generated\Shared\Transfer\SspAssetAssignmentFileParserResponseTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class AssetAssignmentImporter implements AssetAssignmentImporterInterface
{
    public function __construct(
        protected SelfServicePortalRepositoryInterface $repository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetAssignmentFileParserResponseTransfer $sspAssetAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return list<int>
     */
    public function getIdsToAssign(SspAssetAssignmentFileParserResponseTransfer $sspAssetAssignmentFileParserResponseTransfer, int $idFile): array
    {
        $assetReferences = $sspAssetAssignmentFileParserResponseTransfer->getReferencesToAssign();
        if (!$assetReferences) {
            return [];
        }

        return $this->repository->getAssetIdsToAssignByReferences($assetReferences, $idFile);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetAssignmentFileParserResponseTransfer $sspAssetAssignmentFileParserResponseTransfer
     * @param int $idFile
     *
     * @return list<int>
     */
    public function getIdsToUnassign(SspAssetAssignmentFileParserResponseTransfer $sspAssetAssignmentFileParserResponseTransfer, int $idFile): array
    {
        $assetReferences = $sspAssetAssignmentFileParserResponseTransfer->getReferencesToDeassign();
        if (!$assetReferences) {
            return [];
        }

        return $this->repository->getAssetIdsToUnassignByReferences($assetReferences, $idFile);
    }
}
