<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy;

use Generated\Shared\Transfer\BusinessUnitAssignmentFileParserRequestTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\BusinessUnitAssignmentImporterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\BusinessUnitAssignmentFileParserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BusinessUnitCsvImportStrategy implements CsvImportStrategyInterface
{
    /**
     * @var string
     */
    protected const SCOPE_BUSINESS_UNIT = 'business-unit';

    /**
     * @var string
     */
    protected const KEY_BUSINESS_UNIT_IDS_TO_BE_ASSIGNED = 'businessUnitIdsToBeAssigned';

    /**
     * @var string
     */
    protected const KEY_BUSINESS_UNIT_IDS_TO_BE_DEASSIGNED = 'businessUnitIdsToBeDeassigned';

    public function __construct(
        protected BusinessUnitAssignmentFileParserInterface $businessUnitAssignmentFileParser,
        protected BusinessUnitAssignmentImporterInterface $businessUnitAssignmentImporter
    ) {
    }

    public function canHandle(string $scope): bool
    {
        return $scope === static::SCOPE_BUSINESS_UNIT;
    }

    public function extractFileFromForm(FormInterface $form): ?UploadedFile
    {
        return $form->get(AttachFileForm::FIELD_BUSINESS_UNIT_FILE_UPLOAD)->getData();
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
        $businessUnitAssignmentFileParserRequestTransfer = (new BusinessUnitAssignmentFileParserRequestTransfer())->setContent($content);
        $businessUnitReferences = $this->businessUnitAssignmentFileParser->parse($businessUnitAssignmentFileParserRequestTransfer);

        $businessUnitIdsToAssign = $this->businessUnitAssignmentImporter->getIdsToAssign($businessUnitReferences, $idFile);
        $businessUnitIdsToDeassign = $this->businessUnitAssignmentImporter->getIdsToUnassign($businessUnitReferences, $idFile);

        return [
            static::KEY_BUSINESS_UNIT_IDS_TO_BE_ASSIGNED => $businessUnitIdsToAssign,
            static::KEY_BUSINESS_UNIT_IDS_TO_BE_DEASSIGNED => $businessUnitIdsToDeassign,
        ];
    }
}
