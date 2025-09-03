<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy;

use Generated\Shared\Transfer\CompanyAssignmentFileParserRequestTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\CompanyAssignmentImporterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\CompanyAssignmentFileParserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CompanyCsvImportStrategy implements CsvImportStrategyInterface
{
    /**
     * @var string
     */
    protected const SCOPE_COMPANY = 'company';

    /**
     * @var string
     */
    protected const KEY_COMPANY_IDS_TO_BE_ASSIGNED = 'companyIdsToBeAssigned';

    /**
     * @var string
     */
    protected const KEY_COMPANY_IDS_TO_BE_DEASSIGNED = 'companyIdsToBeDeassigned';

    public function __construct(
        protected CompanyAssignmentFileParserInterface $companyAssignmentFileParser,
        protected CompanyAssignmentImporterInterface $companyAssignmentImporter
    ) {
    }

    public function canHandle(string $scope): bool
    {
        return $scope === static::SCOPE_COMPANY;
    }

    public function extractFileFromForm(FormInterface $form): ?UploadedFile
    {
        return $form->get(AttachFileForm::FIELD_COMPANY_FILE_UPLOAD)->getData();
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
        $companyAssignmentFileParserRequestTransfer = (new CompanyAssignmentFileParserRequestTransfer())->setFileContent($content);
        $companyReferences = $this->companyAssignmentFileParser->parseFile($companyAssignmentFileParserRequestTransfer);

        $companyIdsToAssign = $this->companyAssignmentImporter->getIdsToAssign($companyReferences, $idFile);
        $companyIdsToDeassign = $this->companyAssignmentImporter->getIdsToUnassign($companyReferences, $idFile);

        return [
            static::KEY_COMPANY_IDS_TO_BE_ASSIGNED => $companyIdsToAssign,
            static::KEY_COMPANY_IDS_TO_BE_DEASSIGNED => $companyIdsToDeassign,
        ];
    }
}
