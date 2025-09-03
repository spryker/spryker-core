<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy;

use Generated\Shared\Transfer\SspAssetAssignmentFileParserRequestTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\AssetAssignmentImporterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\AssetAssignmentFileParserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AssetCsvImportStrategy implements CsvImportStrategyInterface
{
    /**
     * @var string
     */
    protected const SCOPE_ASSET = 'asset';

    /**
     * @var string
     */
    protected const KEY_SSP_ASSET_IDS_TO_BE_ASSIGNED = 'sspAssetIdsToBeAssigned';

    /**
     * @var string
     */
    protected const KEY_SSP_ASSET_IDS_TO_BE_DEASSIGNED = 'sspAssetIdsToBeDeassigned';

    public function __construct(
        protected AssetAssignmentFileParserInterface $assetAssignmentFileParser,
        protected AssetAssignmentImporterInterface $assetAssignmentImporter
    ) {
    }

    public function canHandle(string $scope): bool
    {
        return $scope === static::SCOPE_ASSET;
    }

    public function extractFileFromForm(FormInterface $form): ?UploadedFile
    {
        return $form->get(AttachFileForm::FIELD_ASSET_FILE_UPLOAD)->getData();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $idFile
     *
     * @return array<string, mixed>
     */
    public function processCsvFile(UploadedFile $file, int $idFile): array
    {
        $content = file_get_contents($file->getPathname());
        if ($content === false) {
            return [];
        }
        $sspAssetAssignmentFileParserRequestTransfer = (new SspAssetAssignmentFileParserRequestTransfer())->setContent($content);
        $sspAssetAssignmentFileParserResponseTransfer = $this->assetAssignmentFileParser->parse($sspAssetAssignmentFileParserRequestTransfer);

        $assetIdsToAssign = $this->assetAssignmentImporter->getIdsToAssign($sspAssetAssignmentFileParserResponseTransfer, $idFile);
        $assetIdsToDeassign = $this->assetAssignmentImporter->getIdsToUnassign($sspAssetAssignmentFileParserResponseTransfer, $idFile);

        return [
            static::KEY_SSP_ASSET_IDS_TO_BE_ASSIGNED => $assetIdsToAssign,
            static::KEY_SSP_ASSET_IDS_TO_BE_DEASSIGNED => $assetIdsToDeassign,
        ];
    }
}
